<template>
  <div>
    <transition appear>
      <div class="flash_message bg-success text-center py-3 my-3 good" v-show="show">
          {{ flash_message }}
      </div>
    </transition>
  </div>
</template>

<script>
  export default {
    props: {
      message: {
        type: String,
      },
    },
    data() {
      return {
        flash_message: this.message,
        show: false,
      }
    },
    mounted() {
      this.show = true;
      setTimeout(() => {
          this.show = false;
      }, 4000);
    }
  }
</script>

<style scoped>
.v-leave-active {
    transition: opacity 1s;
}

.v-leave {
    opacity: 1;
}

.v-leave-to {
    opacity: 0;
}
</style>