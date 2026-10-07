<template>
  <div>
    <h3 class="page-title">{{ form.id ? "Sửa mặt bằng" : "Thêm mặt bằng" }}</h3>

    <div class="card mb-3">
      <div class="card-body fp-tight">
        <div class="row">
          <div class="col-md-3 form-group">
            <label>Danh mục</label>
            <vs-select v-model="form.category_id" class="w-100">
              <vs-select-item v-for="cate in categories" :key="cate.id" :value="String(cate.id)" :text="cate.name" />
            </vs-select>
          </div>
          <div class="col-md-3 form-group">
            <label>Tên trên menu</label>
            <vs-input class="w-100" v-model="form.name" placeholder="Layout Central 1" @input="onPageName" />
          </div>
          <div class="col-md-4 form-group">
            <label>Tiêu đề</label>
            <vs-input class="w-100" v-model="form.title" @input="form.titleAuto = false" />
          </div>
          <div class="col-md-2 form-group">
            <label>Trạng thái</label>
            <vs-select v-model="form.status" class="w-100">
              <vs-select-item value="1" text="Hiện" />
              <vs-select-item value="0" text="Ẩn" />
            </vs-select>
          </div>
        </div>
        <div class="form-group">
          <label>Mô tả</label>
          <textarea class="form-control" rows="2" v-model="form.intro"></textarea>
        </div>
        <details class="fp-more">
          <summary>Đường dẫn, nhãn, thứ tự</summary>
          <div class="row">
            <div class="col-md-5 form-group">
              <label>Đường dẫn</label>
              <vs-input class="w-100" v-model="form.slug" @input="form.slugAuto = false" />
            </div>
            <div class="col-md-5 form-group">
              <label>Nhãn nhỏ</label>
              <vs-input class="w-100" v-model="form.eyebrow" />
            </div>
            <div class="col-md-2 form-group">
              <label>Thứ tự</label>
              <vs-input type="number" class="w-100" v-model="form.sort" />
            </div>
          </div>
        </details>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <div class="fp-editor-head">
          <h4 class="card-title">Bài viết</h4>
          <vs-button size="small" type="border" @click="insertOverview">Tổng quan tòa</vs-button>
          <vs-button size="small" type="border" @click="insertLayout">Layout căn</vs-button>
        </div>
        <TinyMce ref="articleEditor" v-model="form.content" :height="420" :insert-menus="layoutMenus" />
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <div class="fp-editor-head">
          <h4 class="card-title">Nhật ký giỏ hàng <span class="fp-count">{{ journalCount }}</span></h4>
          <vs-button size="small" color="success" type="border" @click="addJournalDay">Thêm ngày</vs-button>
        </div>
        <div
          class="fp-day"
          :class="{ 'is-open': openDay === index }"
          v-for="(day, index) in form.journal"
          :key="'journal-' + index"
        >
          <div class="fp-day-bar" @click="toggleDay(index)">
            <span class="fp-caret">{{ openDay === index ? "▾" : "▸" }}</span>
            <strong>{{ dayTitle(day) }}</strong>
            <span class="fp-count">{{ lineCount(day) }}</span>
            <span class="fp-day-actions" @click.stop>
              <button type="button" @click="duplicateJournalDay(index)">Nhân bản</button>
              <button type="button" class="is-danger" @click="removeDay(index)">Xóa</button>
            </span>
          </div>
          <div class="fp-day-body" v-show="openDay === index">
            <div class="row">
              <div class="col-md-3 form-group">
                <label>Ngày</label>
                <vs-input class="w-100" v-model="day.date" placeholder="07/10/2026" @input="onJournalDate(day)" />
              </div>
              <div class="col-md-3 form-group">
                <label>Thứ</label>
                <vs-input class="w-100" v-model="day.dow" placeholder="Thứ Tư" @input="day.dowAuto = false" />
              </div>
              <div class="col-md-2 form-group">
                <label>Giờ</label>
                <vs-input class="w-100" v-model="day.time" placeholder="07:15" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label>Bán</label>
                <textarea class="form-control" rows="4" v-model="day.sale" placeholder="Mỗi dòng một căn. Central 1 – 1PN – 21 Triệu – Fullnt" @input="grow"></textarea>
              </div>
              <div class="col-md-6 form-group">
                <label>Cho thuê</label>
                <textarea class="form-control" rows="4" v-model="day.rent" placeholder="Mỗi dòng một căn. Landmark 3 – 3PN – 15.5 Tỷ" @input="grow"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row fixxed">
      <div class="col-12">
        <div class="saveButton">
          <vs-button color="primary" @click="save">Lưu mặt bằng</vs-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import TinyMce from "../_common/tinymce";

const OVERVIEW_BLOCK = `
<table class="fp-overview">
<tbody>
<tr>
<td><img src="" alt="Mặt bằng tổng thể" /><p>Layout Landmark 3 (Layout L2)</p></td>
<td>
<h2>Layout Landmark 3</h2>
<ul>
<li>Số tầng: 50 tầng</li>
<li>Số lượng căn hộ/sàn: 13 căn hộ</li>
<li>Số lượng thang máy/toà: 12 thang máy tốc độ cao</li>
<li>Số lượng thang thoát hiểm/toà: 2 thang thoát hiểm với cửa chống cháy</li>
<li>Chiều rộng hành lang: 2,7m</li>
<li>Hầm để xe thông minh với 2 tầng</li>
<li>Căn hộ hiện đại từ 1, 2, 3 phòng ngủ</li>
<li>Căn hộ nhà phố thương mại thông tầng (duplex shophouse)</li>
</ul>
<p>Năm khởi công: 2015</p>
<p>Năm hoàn thành: 2017</p>
</td>
</tr>
</tbody>
</table>`;

const LAYOUT_BLOCK = `
<table class="fp-layout">
<tbody>
<tr>
<td><img src="" alt="Layout L3-01" /><p>Layout L3-01</p></td>
<td>
<h3>Thông tin Layout L3-01</h3>
<p>3PN, 2WC, 1 ban công</p>
<p>Diện tích tim tường: 106,1 m²</p>
<p>Diện tích thông thủy: 98,7 m²</p>
<p>Hướng cửa: Đông Bắc</p>
<p>Hướng ban công: Đông Nam</p>
<p>View: Sông*, công viên*, thành phố*, Landmark 81*</p>
</td>
</tr>
</tbody>
</table>`;

const COLS_BLOCK = `
<table class="fp-cols">
<tbody>
<tr>
<td><p>Cột trái</p></td>
<td><p>Cột phải</p></td>
</tr>
</tbody>
</table>`;

const layoutMenus = [
  { text: "Tổng quan tòa", html: OVERVIEW_BLOCK },
  { text: "Layout một căn", html: LAYOUT_BLOCK },
  { text: "Hai cột trống", html: COLS_BLOCK },
  { text: "Vị trí nhật ký", html: "<p>[[nhat-ky]]</p>" },
];

function formatToday() {
  const date = new Date();
  const pad = (value) => String(value).padStart(2, "0");
  return pad(date.getDate()) + "/" + pad(date.getMonth() + 1) + "/" + date.getFullYear();
}

function blankJournalDay(date) {
  const value = date || "";
  return {
    dow: value ? weekdayFromDate(value) : "",
    date: value,
    time: "07:15",
    dowAuto: true,
    sale: "",
    rent: "",
  };
}

function countLines(value) {
  return String(value || "")
    .split("\n")
    .filter((line) => line.trim()).length;
}

function weekdayFromDate(value) {
  const match = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/.exec(String(value || "").trim());
  if (!match) return "";
  const date = new Date(Number(match[3]), Number(match[2]) - 1, Number(match[1]));
  if (Number.isNaN(date.getTime())) return "";
  return ["Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"][date.getDay()];
}

function toSlug(value) {
  return String(value || "")
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/đ/g, "d")
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

export default {
  name: "floorPlanForm",
  components: { TinyMce },
  data() {
    return {
      categories: [],
      layoutMenus,
      openDay: 0,
      form: this.blankForm(),
    };
  },
  computed: {
    journalCount() {
      const days = this.form.journal.filter((day) => day.date || countLines(day.sale) || countLines(day.rent));
      return days.length ? days.length + " ngày" : "";
    },
  },
  methods: {
    ...mapActions(["listFloorPlanCategory", "saveFloorPlan", "getFloorPlan", "loadings"]),
    blankForm() {
      return {
        id: null,
        category_id: "",
        name: "",
        slug: "",
        slugAuto: true,
        title: "",
        titleAuto: true,
        eyebrow: "MẶT BẰNG TÒA NHÀ",
        intro: "",
        content: "<h2>Layout</h2>\n<ul>\n<li>Số tầng:</li>\n<li>Số lượng căn hộ/sàn:</li>\n</ul>\n<p>[[nhat-ky]]</p>\n<h2>Mặt bằng các tầng</h2>",
        journal: [blankJournalDay()],
        sort: 1,
        status: "1",
      };
    },
    onPageName() {
      const name = String(this.form.name || "").trim();
      if (this.form.slugAuto) this.form.slug = toSlug(name);
      if (this.form.titleAuto) this.form.title = name;
    },
    insertOverview() {
      this.$refs.articleEditor.insertHtml(OVERVIEW_BLOCK);
    },
    insertLayout() {
      this.$refs.articleEditor.insertHtml(LAYOUT_BLOCK);
    },
    addJournalDay() {
      const latest = this.form.journal[0] || {};
      const date = formatToday();
      this.form.journal.unshift({
        dow: weekdayFromDate(date),
        date,
        time: latest.time || "07:15",
        dowAuto: true,
        sale: latest.sale || "",
        rent: latest.rent || "",
      });
      this.openDay = 0;
      this.focusOpenDay();
    },
    duplicateJournalDay(index) {
      const source = this.form.journal[index];
      this.form.journal.splice(index, 0, {
        dow: "",
        date: "",
        time: source.time || "07:15",
        dowAuto: true,
        sale: source.sale,
        rent: source.rent,
      });
      this.openDay = index;
      this.focusOpenDay();
    },
    removeDay(index) {
      this.form.journal.splice(index, 1);
      if (!this.form.journal.length) {
        this.form.journal.push(blankJournalDay());
        this.openDay = 0;
        return;
      }
      if (this.openDay >= this.form.journal.length) this.openDay = this.form.journal.length - 1;
    },
    toggleDay(index) {
      this.openDay = this.openDay === index ? -1 : index;
      if (this.openDay === index) this.growOpen();
    },
    lineCount(day) {
      const sale = countLines(day.sale);
      const rent = countLines(day.rent);
      const parts = [];
      if (sale) parts.push(sale + " bán");
      if (rent) parts.push(rent + " thuê");
      return parts.join(" · ") || "Trống";
    },
    grow(event) {
      const field = event.target;
      field.style.height = "auto";
      field.style.height = Math.max(field.scrollHeight, 88) + "px";
    },
    growOpen() {
      this.$nextTick(() => {
        const root = this.$el.querySelector(".fp-day.is-open");
        if (!root) return;
        root.querySelectorAll("textarea").forEach((field) => {
          field.style.height = "auto";
          field.style.height = Math.max(field.scrollHeight, 88) + "px";
        });
      });
    },
    focusOpenDay() {
      this.growOpen();
      this.$nextTick(() => {
        const field = this.$el.querySelector(".fp-day.is-open input");
        if (field) field.focus();
      });
    },
    onJournalDate(day) {
      if (day.dowAuto) day.dow = weekdayFromDate(day.date);
    },
    dayTitle(day) {
      const building = String(this.form.name || "").trim().replace(/^layout\s+/i, "");
      const short = String(day.date || "").replace(/\/\d{4}$/, "");
      if (building && day.dow && short) {
        return "Giỏ hàng " + building + " – " + day.dow + " ngày " + short;
      }
      return "Ngày mới";
    },
    hydrateJournal(value) {
      let rows = value;
      if (typeof value === "string" && value) {
        try {
          rows = JSON.parse(value);
        } catch (e) {
          rows = [];
        }
      }
      if (!Array.isArray(rows) || !rows.length) return [blankJournalDay()];
      return rows.map((day) => ({
        dow: day.dow || "",
        date: day.date || "",
        time: day.time || "07:15",
        dowAuto: false,
        sale: Array.isArray(day.sale) ? day.sale.join("\n") : day.sale || "",
        rent: Array.isArray(day.rent) ? day.rent.join("\n") : day.rent || "",
      }));
    },
    asHtml(value) {
      if (value == null) return "";
      if (typeof value === "string") return value;
      return "";
    },
    escapeHtml(value) {
      return String(value)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
    },
    articleHtml(data) {
      const content = this.asHtml(data.content);
      const footnote = this.asHtml(data.footnote).trim();
      const title = String(data.footnote_title || "").trim();
      let extra = "";
      if (title && !/<h[23]\b/i.test(footnote)) {
        extra += "<h2>" + this.escapeHtml(title) + "</h2>";
      }
      extra += footnote;
      if (!extra || content.indexOf(extra) !== -1) return content;
      return content + extra;
    },
    load(id) {
      this.loadings(true);
      this.getFloorPlan(id)
        .then((response) => {
          const data = response.data || {};
          this.form = {
            id: data.id,
            category_id: data.category_id ? String(data.category_id) : "",
            name: data.name || "",
            slug: data.slug || "",
            slugAuto: false,
            title: data.title || "",
            titleAuto: false,
            eyebrow: data.eyebrow || "MẶT BẰNG TÒA NHÀ",
            intro: data.intro || "",
            content: this.articleHtml(data),
            journal: this.hydrateJournal(data.journal),
            sort: data.sort != null ? String(data.sort) : "0",
            status: data.status != null ? String(data.status) : "1",
          };
          this.openDay = 0;
          this.growOpen();
          this.loadings(false);
        })
        .catch(() => {
          this.loadings(false);
          this.$error("Không tải được bài mặt bằng");
        });
    },
    boot() {
      this.listFloorPlanCategory().then((response) => {
        this.categories = response.data || [];
        if (this.$route.params.id) this.load(this.$route.params.id);
        else {
          this.form = this.blankForm();
          this.openDay = 0;
        }
      });
    },
    save() {
      if (!String(this.form.name || "").trim()) {
        this.$error("Tên trên menu không được để trống");
        return;
      }
      if (!this.form.category_id) {
        this.$error("Hãy chọn danh mục");
        return;
      }
      this.loadings(true);
      this.saveFloorPlan(this.form)
        .then((response) => {
          this.loadings(false);
          this.$success("Đã lưu mặt bằng");
          const id = response.data && response.data.id;
          if (id && String(this.$route.params.id || "") !== String(id)) {
            this.$router.replace({ name: "floorPlanEdit", params: { id } });
          }
        })
        .catch((error) => {
          this.loadings(false);
          const message = error.response && error.response.data && error.response.data.message;
          this.$error(message || "Lưu mặt bằng thất bại");
        });
    },
  },
  watch: {
    "$route.params.id"() {
      this.boot();
    },
  },
  mounted() {
    this.onSaveKey = (event) => {
      if ((event.ctrlKey || event.metaKey) && event.key === "s") {
        event.preventDefault();
        this.save();
      }
    };
    window.addEventListener("keydown", this.onSaveKey);
    this.boot();
  },
  beforeDestroy() {
    window.removeEventListener("keydown", this.onSaveKey);
  },
};
</script>

<style scoped>
.fp-tight .form-group { margin-bottom: 10px; }
.fp-more { margin-top: 2px; }
.fp-more summary {
  cursor: pointer;
  color: #6b7280;
  font-size: 13px;
  margin-bottom: 8px;
}
.fp-editor-head {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 8px;
}
.fp-editor-head .card-title { margin: 0 auto 0 0; }
.fp-count { color: #6b7280; font-size: 12px; font-weight: 500; }
.fp-day {
  border: 1px solid #eee;
  border-radius: 8px;
  margin-bottom: 8px;
  background: #fafafa;
}
.fp-day-bar {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 12px;
  cursor: pointer;
}
.fp-caret { color: #9ca3af; width: 12px; }
.fp-day-bar strong { font-size: 13px; }
.fp-day-actions { margin-left: auto; white-space: nowrap; }
.fp-day-actions button {
  border: 0;
  background: transparent;
  color: #1a6ef5;
  cursor: pointer;
  margin-left: 8px;
  padding: 0;
}
.fp-day-actions .is-danger { color: #d33; }
.fp-day-body { padding: 0 12px 4px; }
.fp-day-body textarea { min-height: 88px; resize: vertical; overflow: hidden; }
</style>
