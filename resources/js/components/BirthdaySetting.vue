<template>
  <div class="main">
    <p class="mb-0">生年月日（年代表示に使用します）</p>
    <select v-model="year" @change="get_days" name="year">
      <option v-for="n in 100" :value="maxYear - n">
        {{ maxYear - n }}
      </option>
    </select>年
    <select v-model="month" @change="get_days" name="month">
      <option v-for="n in 12" :value="n">
        {{ n }}
      </option>
    </select>月
    <select v-model="day" name="day">
      <option v-for="n in days_max" :value="n">
        {{ n }}
      </option>
    </select>日
  </div>
</template>

<script>
  export default {
    props: {
      setyear: {
        type: String,
        default: '',
      },
      setmonth: {
        type: String,
        default: '',
      },
      setday: {
        type: String,
        default: '',
      }
    },
    data() {
      return {
        year: this.setyear,
        month: this.setmonth,
        day: this.setday,
        days_max: '',
        maxYear: new Date().getFullYear()
      }
    },
    mounted() {
      this.get_days();
    },
    methods: {
    get_days() {
      this.days_max = new Date(this.year, this.month, 0).getDate();
    },
  }
}
</script>