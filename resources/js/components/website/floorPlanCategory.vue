<template>
  <div>
    <h3 class="page-title">Danh mục mặt bằng</h3>
    <p class="text-muted mb-3">Mỗi danh mục là một cột trong menu Mặt bằng. Bài viết gắn trong mục Danh sách mặt bằng.</p>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div
              v-for="(item, key) in objData"
              :key="'floor-cate-' + key"
              class="faq-admin-item"
            >
              <div class="faq-admin-item__head">
                <strong>Danh mục #{{ key + 1 }}</strong>
                <label
                  v-if="objData.length > 1"
                  class="faq-admin-item__remove"
                  title="Xóa"
                  @click="removeItem(key)"
                >
                  <vs-icon icon="clear"></vs-icon>
                </label>
              </div>

              <div class="form-group">
                <label>Tên danh mục <span class="text-danger">*</span></label>
                <vs-input
                  class="w-100"
                  v-model="item.name"
                  placeholder="VD: Khu Central"
                />
              </div>

              <div class="row">
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
            </div>

            <vs-button color="success" type="border" @click="addItem">
              <vs-icon icon="add"></vs-icon> Thêm danh mục
            </vs-button>
          </div>
        </div>
      </div>
    </div>

    <div class="row fixxed">
      <div class="col-12">
        <div class="saveButton">
          <vs-button color="primary" @click="save">Lưu danh mục</vs-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "floorPlanCategory",
  data() {
    return {
      objData: [this.defaultItem()],
    };
  },
  methods: {
    ...mapActions(["saveFloorPlanCategory", "listFloorPlanCategory", "loadings"]),

    defaultItem() {
      return {
        name: "",
        sort: 1,
        status: "1",
      };
    },

    addItem() {
      const next = this.defaultItem();
      next.sort = this.objData.length + 1;
      this.objData.push(next);
    },

    removeItem(index) {
      this.objData.splice(index, 1);
    },

    load() {
      this.loadings(true);
      this.listFloorPlanCategory()
        .then((response) => {
          this.loadings(false);
          const data = response.data || [];
          if (data.length) {
            this.objData = data.map((item) => ({
              id: item.id,
              name: item.name || "",
              sort: item.sort != null ? String(item.sort) : "0",
              status: item.status != null ? String(item.status) : "1",
            }));
          }
        })
        .catch(() => {
          this.loadings(false);
        });
    },

    save() {
      const invalid = this.objData.some((item) => !String(item.name || "").trim());
      if (invalid) {
        this.$error("Tên danh mục không được để trống");
        return;
      }

      this.loadings(true);
      this.saveFloorPlanCategory({ items: this.objData })
        .then(() => {
          this.loadings(false);
          this.$success("Lưu danh mục mặt bằng thành công");
          this.load();
        })
        .catch(() => {
          this.loadings(false);
          this.$error("Lưu danh mục mặt bằng thất bại");
        });
    },
  },
  mounted() {
    this.load();
  },
};
</script>

<style scoped>
.faq-admin-item {
  border: 1px solid #eee;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 16px;
  background: #fafafa;
}
.faq-admin-item__head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 12px;
}
.faq-admin-item__remove {
  cursor: pointer;
  margin: 0;
  color: #e55;
}
</style>
