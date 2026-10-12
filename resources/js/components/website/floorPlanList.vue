<template>
  <div>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Danh sách mặt bằng</h4>
            <vs-button type="gradient" style="float:right;" @click="create">Thêm mặt bằng</vs-button>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Tên trên menu</th>
                    <th>Danh mục</th>
                    <th>Thứ tự</th>
                    <th>Hiển thị</th>
                    <th>Nổi bật</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!rows.length">
                    <td colspan="6">Chưa có bài mặt bằng.</td>
                  </tr>
                  <tr v-for="row in rows" :key="row.id">
                    <td>{{ row.name }}</td>
                    <td>{{ row.category ? row.category.name : "—" }}</td>
                    <td>{{ row.sort }}</td>
                    <td>
                      <el-switch
                        :value="String(row.status) === '1'"
                        active-color="#13ce66"
                        inactive-color="#ff4949"
                        @change="val => toggle(row, 'status', val)"
                      />
                    </td>
                    <td>
                      <el-switch
                        :value="String(row.featured) === '1'"
                        active-color="#B28E4B"
                        inactive-color="#C0CCDA"
                        @change="val => toggle(row, 'featured', val)"
                      />
                    </td>
                    <td class="text-right">
                      <vs-button size="small" @click="edit(row.id)">Sửa</vs-button>
                      <vs-button size="small" color="danger" type="border" @click="remove(row)">Xóa</vs-button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "floorPlanList",
  data() {
    return { rows: [] };
  },
  methods: {
    ...mapActions(["listFloorPlan", "deleteFloorPlan", "toggleFloorPlanField", "loadings"]),
    toggle(row, field, on) {
      const value = on ? 1 : 0;
      this.toggleFloorPlanField({ id: row.id, field, value })
        .then(() => {
          row[field] = value;
          this.$success("Đã cập nhật");
        })
        .catch(() => this.$error("Cập nhật thất bại"));
    },
    load() {
      this.loadings(true);
      this.listFloorPlan()
        .then((response) => {
          this.rows = response.data || [];
          this.loadings(false);
        })
        .catch(() => this.loadings(false));
    },
    create() {
      this.$router.push({ name: "floorPlanCreate" });
    },
    edit(id) {
      this.$router.push({ name: "floorPlanEdit", params: { id } });
    },
    remove(row) {
      if (!window.confirm("Xóa bài “" + row.name + "”?")) return;
      this.loadings(true);
      this.deleteFloorPlan(row.id)
        .then(() => {
          this.$success("Đã xóa");
          this.load();
        })
        .catch(() => {
          this.loadings(false);
          this.$error("Xóa thất bại");
        });
    },
  },
  mounted() {
    this.load();
  },
};
</script>
