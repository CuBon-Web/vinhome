<template>
  <div>
    <h3 class="page-title">Bảng giá trang chủ</h3>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>Nhãn nhỏ</label>
              <vs-input class="w-100" v-model="meta.eyebrow" placeholder="Bảng giá tham khảo · T9/2026" />
            </div>
            <div class="form-group">
              <label>Tiêu đề</label>
              <vs-input class="w-100" v-model="meta.title" placeholder="Bảng giá bán căn hộ Vinhomes Central Park" />
            </div>
            <div class="form-group">
              <label>Ghi chú dưới bảng</label>
              <vs-textarea v-model="meta.note" rows="3" />
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
              :key="'price-' + key"
              style="border:1px solid #eee;border-radius:8px;padding:12px;margin-bottom:16px"
            >
              <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
                <strong>Dòng {{ key + 1 }}</strong>
                <label
                  style="cursor:pointer;margin:0;color:#e55"
                  title="Xóa dòng"
                  @click="removeItem(key)"
                >
                  <vs-icon icon="clear"></vs-icon>
                </label>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Loại căn <span class="text-danger">*</span></label>
                  <vs-input class="w-100" v-model="item.type" placeholder="1 phòng ngủ" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Diện tích</label>
                  <vs-input class="w-100" v-model="item.area" placeholder="Diện tích 36 – 56m²" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Giá</label>
                  <vs-input class="w-100" v-model="item.price" placeholder="6 – 8 tỷ" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Thứ tự</label>
                  <vs-input type="number" v-model="item.sort" class="w-100" />
                </div>
              </div>
              <div class="col-md-4">
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
              <vs-icon icon="add"></vs-icon> Thêm dòng
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
  name: 'homePrice',
  data() {
    return {
      meta: { eyebrow: '', title: '', note: '' },
      objData: [this.defaultItem()],
    };
  },
  methods: {
    ...mapActions(['saveHomePrice', 'listHomePrice', 'loadings']),
    defaultItem() {
      return { type: '', area: '', price: '', sort: this.objData ? this.objData.length + 1 : 1, status: '1' };
    },
    addItem() {
      this.objData.push({
        type: '',
        area: '',
        price: '',
        sort: String(this.objData.length + 1),
        status: '1',
      });
    },
    removeItem(index) {
      this.objData.splice(index, 1);
    },
    load() {
      this.loadings(true);
      this.listHomePrice().then(response => {
        this.loadings(false);
        const meta = response.meta || {};
        this.meta = {
          eyebrow: meta.eyebrow || '',
          title: meta.title || '',
          note: meta.note || '',
        };
        const data = response.data || [];
        if (data.length) {
          this.objData = data.map(d => ({
            type: d.type || '',
            area: d.area || '',
            price: d.price || '',
            sort: d.sort != null ? String(d.sort) : '0',
            status: d.status != null ? String(d.status) : '1',
          }));
        }
      }).catch(() => { this.loadings(false); });
    },
    save() {
      const invalid = this.objData.some(item => !String(item.type || '').trim());
      if (invalid) {
        this.$error('Loại căn không được để trống');
        return;
      }
      this.loadings(true);
      this.saveHomePrice({
        eyebrow: this.meta.eyebrow,
        title: this.meta.title,
        note: this.meta.note,
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
