<template>
  <div class="inv-log">
    <div class="form-group">
      <label>Dòng giá / diện tích</label>
      <vs-input class="w-100" v-model="form.price_note" placeholder="Giá từ 6 – 8 tỷ · 36 – 56m²" />
    </div>
    <div class="form-group">
      <label>Nhãn số căn</label>
      <vs-input class="w-100" v-model="form.badge" placeholder="24 căn" />
    </div>

    <div class="inv-day" v-for="(day, dayIndex) in form.logs" :key="'day-' + dayIndex">
      <div class="inv-day-head">
        <strong>Ngày {{ dayIndex + 1 }}</strong>
        <vs-button size="small" color="danger" type="border" @click="removeDay(dayIndex)">Xóa ngày</vs-button>
      </div>
      <div class="row">
        <div class="col-md-3 form-group">
          <label>Thứ</label>
          <vs-input class="w-100" v-model="day.dow" placeholder="Thứ Năm" />
        </div>
        <div class="col-md-3 form-group">
          <label>Ngày</label>
          <vs-input class="w-100" v-model="day.date" placeholder="24/09/2026" />
        </div>
        <div class="col-md-3 form-group">
          <label>Giờ</label>
          <vs-input class="w-100" v-model="day.time" placeholder="07:00" />
        </div>
      </div>
      <div class="form-group">
        <label>Tiêu đề nhật ký</label>
        <vs-input class="w-100" v-model="day.title" placeholder="Giỏ hàng 1PN ..." />
      </div>

      <div class="inv-group" v-for="(group, groupIndex) in day.groups" :key="'group-' + dayIndex + '-' + groupIndex">
        <div class="inv-day-head">
          <label>Khu</label>
          <vs-button size="small" color="danger" type="border" @click="removeGroup(day, groupIndex)">Xóa khu</vs-button>
        </div>
        <vs-input class="w-100" v-model="group.name" placeholder="Khu Central" />
        <div class="inv-item" v-for="(item, itemIndex) in group.items" :key="'item-' + dayIndex + '-' + groupIndex + '-' + itemIndex">
          <vs-input v-model="item.label" placeholder="Tòa / căn" />
          <vs-input class="w-100" v-model="item.text" placeholder="1PN – 7.1 Tỷ – Sổ hồng" />
          <vs-button size="small" color="danger" type="flat" icon="clear" @click="group.items.splice(itemIndex, 1)"></vs-button>
        </div>
        <vs-button size="small" type="border" @click="group.items.push(blankItem())">Thêm căn</vs-button>
      </div>
      <vs-button size="small" type="border" color="primary" @click="addGroup(day)">Thêm khu</vs-button>
    </div>

    <vs-button color="success" type="border" @click="addDay">Thêm ngày</vs-button>
  </div>
</template>

<script>
function blankItem() {
  return { label: "", text: "" };
}
function blankGroup() {
  return { name: "", items: [blankItem()] };
}
function blankDay() {
  return { dow: "", date: "", title: "", time: "", groups: [blankGroup()] };
}
function blankForm() {
  return { price_note: "", badge: "", logs: [] };
}

export default {
  name: "InventoryLogEditor",
  props: {
    value: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      form: blankForm(),
      lastEmitted: "",
    };
  },
  watch: {
    value: {
      immediate: true,
      handler(value) {
        if (value === this.lastEmitted) return;
        this.form = this.parse(value);
      },
    },
    form: {
      deep: true,
      handler() {
        const raw = JSON.stringify(this.form);
        if (raw === this.lastEmitted) return;
        this.lastEmitted = raw;
        this.$emit("input", raw);
      },
    },
  },
  methods: {
    blankItem,
    parse(value) {
      if (!value) return blankForm();
      try {
        const data = JSON.parse(value);
        if (!data || typeof data !== "object" || !Array.isArray(data.logs)) {
          return blankForm();
        }
        return {
          price_note: data.price_note || "",
          badge: data.badge || "",
          logs: data.logs.map((day) => ({
            dow: day.dow || "",
            date: day.date || "",
            title: day.title || "",
            time: day.time || "",
            groups: Array.isArray(day.groups)
              ? day.groups.map((group) => ({
                  name: group.name || "",
                  items: Array.isArray(group.items)
                    ? group.items.map((item) => ({
                        label: item.label || "",
                        text: item.text || "",
                      }))
                    : [blankItem()],
                }))
              : [blankGroup()],
          })),
        };
      } catch (e) {
        return blankForm();
      }
    },
    addDay() {
      this.form.logs.push(blankDay());
    },
    removeDay(index) {
      this.form.logs.splice(index, 1);
    },
    addGroup(day) {
      day.groups.push(blankGroup());
    },
    removeGroup(day, index) {
      day.groups.splice(index, 1);
    },
  },
};
</script>

<style scoped>
.inv-day,
.inv-group,
.inv-item {
  border: 1px solid #e5e1d8;
  border-radius: 8px;
  padding: 12px;
  margin: 12px 0;
  background: #fff;
}
.inv-group {
  background: #faf8f4;
}
.inv-item {
  display: grid;
  grid-template-columns: 180px 1fr auto;
  gap: 8px;
  align-items: center;
  background: #fff;
}
.inv-day-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}
</style>
