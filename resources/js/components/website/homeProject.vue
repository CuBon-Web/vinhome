<template>
  <div>
    <h3 class="page-title">Khám phá dự án</h3>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label>Nhãn nhỏ</label>
                  <vs-input class="w-100" v-model="meta.eyebrow" placeholder="Khám phá dự án" />
                </div>
                <div class="form-group">
                  <label>Tiêu đề</label>
                  <vs-input class="w-100" v-model="meta.title" placeholder="Biểu tượng mới của Thành phố Hồ Chí Minh" />
                </div>
                <div class="form-group">
                  <label>Đoạn mô tả 1</label>
                  <vs-textarea v-model="meta.paragraph_1" rows="4" />
                  <small class="text-muted">Có thể chèn HTML, ví dụ &lt;strong&gt;Tập đoàn Vingroup&lt;/strong&gt;.</small>
                </div>
                <div class="form-group">
                  <label>Đoạn mô tả 2</label>
                  <vs-textarea v-model="meta.paragraph_2" rows="3" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Ảnh</label>
                  <image-upload type="avatar" v-model="meta.image" title="home-project"></image-upload>
                </div>
                <div class="form-group">
                  <label>Đường dẫn ảnh</label>
                  <vs-input class="w-100" v-model="meta.image" placeholder="https://..." />
                </div>
                <div class="form-group">
                  <label>Mô tả ảnh (alt)</label>
                  <vs-input class="w-100" v-model="meta.image_alt" />
                </div>
                <div class="form-group">
                  <label>Chú thích ảnh</label>
                  <vs-input class="w-100" v-model="meta.caption" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div
              class="row"
              v-for="(item, key) in objData"
              :key="'spec-' + key"
              style="border:1px solid #eee;border-radius:8px;padding:12px;margin-bottom:16px"
            >
              <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
                <strong>Thông số {{ key + 1 }}</strong>
                <label
                  style="cursor:pointer;margin:0;color:#e55"
                  title="Xóa thông số"
                  @click="removeItem(key)"
                >
                  <vs-icon icon="clear"></vs-icon>
                </label>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Tên <span class="text-danger">*</span></label>
                  <vs-input class="w-100" v-model="item.label" placeholder="Chủ đầu tư" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Giá trị</label>
                  <vs-input class="w-100" v-model="item.value" placeholder="Tập đoàn Vingroup" />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Thứ tự</label>
                  <vs-input type="number" v-model="item.sort" class="w-100" />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Trạng thái</label>
                  <vs-select v-model="item.status">
                    <vs-select-item value="1" text="Hiện" />
                    <vs-select-item value="0" text="Ẩn" />
                  </vs-select>
                </div>
              </div>
            </div>

            <vs-button color="success" type="border" @click="addItem">
              <vs-icon icon="add"></vs-icon> Thêm thông số
            </vs-button>
          </div>
        </div>
      </div>
    </div>

    <div class="row fixxed">
      <div class="col-12">
        <div class="saveButton">
          <vs-button color="primary" @click="save">Lưu</vs-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex';
export default {
  name: 'homeProject',
  data() {
    return {
      meta: {
        eyebrow: '',
        title: '',
        paragraph_1: '',
        paragraph_2: '',
        image: '',
        image_alt: '',
        caption: '',
      },
      objData: [{ label: '', value: '', sort: '1', status: '1' }],
    };
  },
  methods: {
    ...mapActions(['saveHomeProject', 'listHomeProject', 'loadings']),
    addItem() {
      this.objData.push({
        label: '',
        value: '',
        sort: String(this.objData.length + 1),
        status: '1',
      });
    },
    removeItem(index) {
      this.objData.splice(index, 1);
    },
    load() {
      this.loadings(true);
      this.listHomeProject().then(response => {
        this.loadings(false);
        const meta = response.meta || {};
        this.meta = {
          eyebrow: meta.eyebrow || '',
          title: meta.title || '',
          paragraph_1: meta.paragraph_1 || '',
          paragraph_2: meta.paragraph_2 || '',
          image: meta.image || '',
          image_alt: meta.image_alt || '',
          caption: meta.caption || '',
        };
        const data = response.data || [];
        if (data.length) {
          this.objData = data.map(d => ({
            label: d.label || '',
            value: d.value || '',
            sort: d.sort != null ? String(d.sort) : '0',
            status: d.status != null ? String(d.status) : '1',
          }));
        }
      }).catch(() => { this.loadings(false); });
    },
    save() {
      const invalid = this.objData.some(item => !String(item.label || '').trim());
      if (invalid) {
        this.$error('Tên thông số không được để trống');
        return;
      }
      this.loadings(true);
      this.saveHomeProject({
        eyebrow: this.meta.eyebrow,
        title: this.meta.title,
        paragraph_1: this.meta.paragraph_1,
        paragraph_2: this.meta.paragraph_2,
        image: this.meta.image,
        image_alt: this.meta.image_alt,
        caption: this.meta.caption,
        items: this.objData,
      }).then(() => {
        this.loadings(false);
        this.$success('Lưu thành công');
        this.load();
      }).catch(() => {
        this.loadings(false);
        this.$error('Lưu thất bại');
      });
    },
  },
  mounted() {
    this.load();
  },
};
</script>
