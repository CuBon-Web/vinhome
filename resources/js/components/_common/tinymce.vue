<template>
  <div class="tinymce-wrap">
    <editor
      :init="{...init}"
      v-model="localContent"
      model-events="input keyup change undo redo"
    ></editor>
    <div class="seo-assistant-inline">
      <div class="seo-top">
        <div class="seo-score">{{ seoAnalysis.score }}</div>
        <div>
          <p class="seo-title">Điểm SEO của nội dung trên</p>
          <p class="seo-subtitle">{{ seoAnalysis.summary }}</p>
        </div>
        <div class="seo-meta">
          <span>{{ seoAnalysis.wordCount }} từ</span>
          <span>~{{ seoAnalysis.readingMinutes }} phút đọc</span>
        </div>
      </div>

      <div class="seo-checklist">
        <div class="seo-item" v-for="item in seoAnalysis.checklist" :key="item.key">
          <i :class="item.ok ? 'el-icon-success seo-ok' : 'el-icon-warning seo-warn'"></i>
          <span>{{ item.label }}</span>
        </div>
      </div>

      <div class="seo-tools">
        <el-button size="mini" @click="insertSeoOutline">Chèn bố cục SEO</el-button>
        <el-button size="mini" @click="insertFaqBlock">Chèn FAQ schema</el-button>
        <el-button size="mini" @click="insertInternalLinkHint">Gợi ý internal link</el-button>
      </div>
    </div>
  </div>
</template>

<script>
// Import TinyMCE
import tinymce from "tinymce/tinymce";
// A theme is also required
import "tinymce/themes/silver";
// Any plugins you want to use has to be imported
import "tinymce/plugins/advlist";
import "tinymce/plugins/wordcount";
import "tinymce/plugins/autolink";
import "tinymce/plugins/autosave";
import "tinymce/plugins/charmap";
import "tinymce/plugins/codesample";
import "tinymce/plugins/contextmenu";
import "tinymce/plugins/emoticons";
import "tinymce/plugins/fullscreen";
import "tinymce/plugins/hr";
import "tinymce/plugins/imagetools";
import "tinymce/plugins/insertdatetime";
import "tinymce/plugins/link";
import "tinymce/plugins/media";
import "tinymce/plugins/noneditable";
import "tinymce/plugins/paste";
import "tinymce/plugins/print";
import "tinymce/plugins/searchreplace";
import "tinymce/plugins/tabfocus";
import "tinymce/plugins/template";
import "tinymce/plugins/textpattern";
import "tinymce/plugins/visualblocks";
import "tinymce/plugins/anchor";
import "tinymce/plugins/autoresize";
import "tinymce/plugins/bbcode";
import "tinymce/plugins/code";
import "tinymce/plugins/colorpicker";
import "tinymce/plugins/directionality";
import "tinymce/plugins/fullpage";
import "tinymce/plugins/help";
import "tinymce/plugins/image";
import "tinymce/plugins/importcss";
import "tinymce/plugins/legacyoutput";
import "tinymce/plugins/lists";
import "tinymce/plugins/nonbreaking";
import "tinymce/plugins/pagebreak";
import "tinymce/plugins/preview";
import "tinymce/plugins/save";
import "tinymce/plugins/spellchecker";
import "tinymce/plugins/table";
import "tinymce/plugins/textcolor";
import "tinymce/plugins/toc";
import "tinymce/plugins/visualchars";

import "tinymce/skins/ui/oxide/skin.min.css";
import Editor from "@tinymce/tinymce-vue";

const FP_COLUMN_STYLE = `
  body { font-family: "Be Vietnam Pro", sans-serif; font-size: 15px; }
  table.fp-layout, table.fp-overview, table.fp-cols, table.fp-floor { width: 100%; border-collapse: collapse; margin: 10px 0; }
  table.fp-layout td, table.fp-overview td, table.fp-cols td, table.fp-floor td { width: 50%; vertical-align: top; border: 1px dashed #d9cbb3; padding: 10px; }
  table.fp-layout img, table.fp-overview img, table.fp-cols img, table.fp-floor img { max-width: 100%; height: auto; }
  table.fp-floor td.fp-floor-media, table.fp-floor td.fp-floor-panels { width: 50%; }
  .fp-acc { border: 1px solid #e7e1d8; border-radius: 10px; margin: 0 0 10px; background: #fff; overflow: hidden; }
  .fp-acc summary { list-style: none; cursor: pointer; padding: 12px 40px 12px 14px; position: relative; font-weight: 600; font-size: 14px; color: #1c1a16; }
  .fp-acc summary::-webkit-details-marker { display: none; }
  .fp-acc summary::after { content: "+"; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-size: 18px; color: #8c6e36; font-weight: 400; }
  .fp-acc[open] summary::after { content: "–"; }
  .fp-acc-body { padding: 0 14px 14px; border-top: 1px solid #f0ebe3; }
  .fp-acc-body img { display: block; width: 100%; margin-top: 12px; border: 1px solid #ece6dc; border-radius: 10px; }
  .fp-acc-body p { font-size: 13px; color: #9a9186; font-style: italic; text-align: center; margin: 10px 0 0; }
`;

export default {
  name: "tinymce",
  components: {
    editor: Editor
  },
  props: {
    value: {
      type: String,
      default: ''
    },
    focusKeyword: {
      type: String,
      default: ""
    },
    height: {
      type: Number,
      default: 500
    },
    insertMenus: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      editor: null,
      cTinyMce: null,
      checkerTimeout: null,
      isTyping: false,
      localContent: this.value || "",
      init: {
        paste_data_images: true,
        plugins: [
          "advlist autolink lists link image charmap print preview hr anchor pagebreak",
          "searchreplace wordcount visualblocks visualchars code fullscreen",
          "insertdatetime media nonbreaking save table contextmenu directionality",
          "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar1:
          "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons" + (this.insertMenus.length ? " | fpcolumns | fpaddacc fpremoveacc" : ""),
        content_style: this.insertMenus.length ? FP_COLUMN_STYLE : "",
        extended_valid_elements: "details[class|open],summary,div[class|style]",
        valid_children: "+body[style],+td[details|div|summary],+details[summary|div|#text]",
        image_advtab: true,
        height: this.height,
        images_upload_handler: this.images_upload_handler,
        setup: (editor) => {
          this.editor = editor;
          const syncContent = () => {
            const html = editor.getContent();
            if (html !== this.localContent) {
              this.localContent = html;
            }
          };
          editor.on("keyup", syncContent);
          editor.on("change", syncContent);
          editor.on("input", syncContent);
          editor.on("undo", syncContent);
          editor.on("redo", syncContent);
          if (this.insertMenus.length) {
            const menus = this.insertMenus;
            const floorAccordionHtml = () =>
              '<details class="fp-acc"><summary>Căn hộ mới</summary><div class="fp-acc-body"><img src="" alt="" /><p>Chú thích layout</p></div></details>';
            const floorPanelsCell = () => {
              const node = editor.selection.getNode();
              return editor.dom.getParent(node, "td.fp-floor-panels")
                || (editor.dom.getParent(node, "table.fp-floor") || { querySelector: () => null }).querySelector("td.fp-floor-panels");
            };
            const warnFloor = (text) => {
              editor.notificationManager.open({ text, type: "warning", timeout: 2500 });
            };
            editor.ui.registry.addMenuButton("fpcolumns", {
              text: "2 cột",
              tooltip: "Chèn layout 2 cột tại vị trí con trỏ",
              fetch: (callback) => {
                callback(menus.map((item) => ({
                  type: "menuitem",
                  text: item.text,
                  onAction: () => {
                    editor.focus();
                    editor.insertContent(item.html);
                  },
                })));
              },
            });
            editor.ui.registry.addButton("fpaddacc", {
              text: "Thêm dropdown",
              tooltip: "Thêm một mục trong cột dropdown của mặt bằng tầng",
              onAction: () => {
                const cell = floorPanelsCell();
                if (!cell) {
                  warnFloor("Đặt con trỏ trong layout mặt bằng tầng rồi thêm dropdown.");
                  return;
                }
                const current = editor.dom.getParent(editor.selection.getNode(), "details.fp-acc");
                editor.undoManager.transact(() => {
                  const holder = editor.dom.create("div", {}, floorAccordionHtml());
                  const item = holder.firstChild;
                  if (current && current.parentNode === cell) {
                    editor.dom.insertAfter(item, current);
                  } else {
                    cell.appendChild(item);
                  }
                  const summary = item.querySelector("summary");
                  if (summary) editor.selection.setCursorLocation(summary, 0);
                });
                editor.nodeChanged();
                editor.fire("change");
              },
            });
            editor.ui.registry.addButton("fpremoveacc", {
              text: "Xóa dropdown",
              tooltip: "Xóa dropdown đang chọn",
              onAction: () => {
                const current = editor.dom.getParent(editor.selection.getNode(), "details.fp-acc");
                if (!current) {
                  warnFloor("Đặt con trỏ vào dropdown cần xóa.");
                  return;
                }
                const sibling = (node) => (node && node.matches && node.matches("details.fp-acc") ? node : null);
                const next = sibling(current.nextElementSibling) || sibling(current.previousElementSibling);
                editor.undoManager.transact(() => {
                  editor.dom.remove(current);
                });
                if (next && next.querySelector) {
                  const summary = next.querySelector("summary");
                  if (summary) editor.selection.setCursorLocation(summary, 0);
                }
                editor.nodeChanged();
                editor.fire("change");
              },
            });
            editor.ui.registry.addContextToolbar("fpfloortools", {
              predicate: (node) => !!editor.dom.getParent(node, "table.fp-floor"),
              items: "fpaddacc fpremoveacc",
              position: "selection",
              scope: "node",
            });
          }
        }
      }
    };
  },
  mounted() {
      
  },
  computed:{
    content:{
      get() {
        return this.localContent;
      },
      set(content) {
        this.localContent = content;
      }
    },
    plainText() {
      return (this.localContent || "")
        .replace(/<style[\s\S]*?<\/style>/gi, " ")
        .replace(/<script[\s\S]*?<\/script>/gi, " ")
        .replace(/<[^>]+>/g, " ")
        .replace(/\s+/g, " ")
        .trim();
    },
    seoAnalysis() {
      const html = this.localContent || "";
      const keyword = (this.focusKeyword || "").trim().toLowerCase();
      const text = this.plainText.toLowerCase();
      const wordCount = this.plainText ? this.plainText.split(" ").filter(Boolean).length : 0;
      const hasContent = wordCount > 0;
      const hasKeyword = !!keyword;
      const readingMinutes = Math.max(1, Math.ceil(wordCount / 220));
      const h2Count = (html.match(/<h2[\s>]/gi) || []).length;
      const h3Count = (html.match(/<h3[\s>]/gi) || []).length;
      const imageTags = (html.match(/<img[\s\S]*?>/gi) || []);
      const imageCount = imageTags.length;
      const imageWithAlt = imageTags.filter(tag => /alt\s*=\s*["'][^"']+["']/i.test(tag)).length;
      const internalLinks = (html.match(/<a[\s\S]*?href=["'](\/|https?:\/\/[^"']*yourdomain|https?:\/\/[^"']*jicafood)[^"']*["']/gi) || []).length;
      const keywordInContent = hasKeyword ? text.includes(keyword) : false;
      const keywordInHeadings = hasKeyword ? new RegExp(`<h[23][^>]*>[^<]*${this.escapeRegex(keyword)}[^<]*<\\/h[23]>`, "i").test(html) : false;
      const checklist = [
        { key: "words", label: "Nội dung tối thiểu 400 từ", ok: wordCount >= 400 },
        { key: "h2h3", label: "Có cấu trúc heading H2/H3", ok: hasContent && h2Count >= 2 && h3Count >= 1 },
        { key: "images", label: "Ảnh có alt text", ok: hasContent && imageCount > 0 && imageWithAlt === imageCount },
        { key: "links", label: "Có internal link", ok: hasContent && internalLinks >= 1 },
        { key: "kw-content", label: "Từ khóa chính có trong nội dung", ok: hasKeyword && keywordInContent },
        { key: "kw-heading", label: "Từ khóa chính xuất hiện trong heading", ok: hasKeyword && keywordInHeadings }
      ];
      const okCount = checklist.filter(item => item.ok).length;
      const score = Math.round((okCount / checklist.length) * 100);
      let summary = "Cần tối ưu thêm để chuẩn SEO";
      if (score >= 85) summary = "Rất tốt, có thể xuất bản";
      else if (score >= 65) summary = "Khá ổn, nên chỉnh thêm vài điểm";
      return { score, summary, wordCount, readingMinutes, checklist };
    }
  },
  watch: {
    value(newValue) {
      if ((newValue || "") !== (this.localContent || "")) {
        this.localContent = newValue || "";
      }
    },
    localContent(newValue) {
      this.$emit("input", newValue);
    },
    seoAnalysis: {
      deep: true,
      handler(val) {
        this.$emit("seo-analysis", val);
      }
    }
  },
  beforeDestroy() {},
  methods: {
    escapeRegex(text) {
      return (text || "").replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    },
    appendHtml(htmlBlock) {
      this.content = `${this.localContent || ""}\n${htmlBlock}`;
    },
    insertHtml(html) {
      if (this.editor) {
        this.editor.focus();
        this.editor.insertContent(html);
        return;
      }
      this.appendHtml(html);
    },
    insertSeoOutline() {
      this.appendHtml(`
<h2>Gioi thieu</h2>
<p>Tom tat van de, boi canh va loi ich chinh cho nguoi doc.</p>
<h2>Noi dung chinh</h2>
<h3>Muc 1</h3>
<p>Trinh bay thong tin cu the, co vi du minh hoa.</p>
<h3>Muc 2</h3>
<p>Lam ro cac buoc thuc hien, so lieu, kinh nghiem.</p>
<h2>Ket luan</h2>
<p>Chot lai gia tri va de xuat hanh dong tiep theo.</p>`);
    },
    insertFaqBlock() {
      this.appendHtml(`
<h2>Cau hoi thuong gap</h2>
<h3>Cau hoi 1</h3>
<p>Tra loi ngan gon, truc tiep vao van de.</p>
<h3>Cau hoi 2</h3>
<p>Tra loi kem vi du de nguoi doc de hieu.</p>`);
    },
    insertInternalLinkHint() {
      this.appendHtml(`
<p><strong>Goi y internal link:</strong> Chen 1-2 lien ket den bai viet lien quan hoac trang dich vu san pham.</p>`);
    },
    images_upload_handler: function(blobInfo, success, failure) {
      var xhr, formData;
      xhr = new XMLHttpRequest();
      xhr.withCredentials = false;
      xhr.open("POST", __ENV__.link + 'api/upload-image');
      xhr.onload = function() {
        var json;

        if (xhr.status != 200) {
          failure("HTTP Error: " + xhr.status);
          return;
        }
        
        json = JSON.parse(xhr.responseText);
        success(json.path);
      };
      formData = new FormData();
      formData.append("img", blobInfo.blob(), blobInfo.filename());
      xhr.send(formData);
    }
  }
};
</script>

<style>
.tinymce-wrap {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.seo-assistant-inline {
  border: 1px solid #dce4f6;
  border-radius: 10px;
  padding: 10px;
  background: #fbfcff;
}

.seo-top {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 8px;
}

.seo-score {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #eef2ff;
  color: #2f44a0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
}

.seo-title {
  margin: 0;
  font-weight: 600;
}

.seo-subtitle {
  margin: 0;
  font-size: 12px;
  color: #6a7490;
}

.seo-meta {
  margin-left: auto;
  display: flex;
  gap: 10px;
  font-size: 12px;
  color: #6a7490;
}

.seo-checklist {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 6px;
  margin-bottom: 8px;
}

.seo-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
}

.seo-ok {
  color: #2e7d32;
}

.seo-warn {
  color: #ed6c02;
}

.seo-tools {
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
}
</style>