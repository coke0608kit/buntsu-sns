<template>
  <div>
    <p class="card-text">お支払額 {{ quantity * this.price }}円（送料/消費税込）</p>
    <input type="hidden" name="price" form="checkout" :value=this.price />
    <input type="number" name="quantity" form="checkout" style="width: 50px" v-model.number="quantity" @change="changeQuantity"/>セット
    <input type="hidden" name="item" form="checkout" :value=this.item />
  </div>
</template>

<script>
  export default {
    props: {
      price: {
        type: Number,
      },
      item: {
        type: String,
      },
    },
    data() {
      return {
        quantity: 1,
      }
    },
    methods: {
      changeQuantity() {
        if (this.quantity <= 0) {
          this.quantity = 1;
        }
      }
    }
  }
</script>