<template>
  <div>
    <button
      class="btn-sm shadow-none border border-danger p-2"
      style="width: max-content;font-size: 10px;"
      :class="buttonColor"
      @click="clickBlock"
    >
      {{ buttonText }}
    </button>
  </div>
</template>

<script>
  export default {
    props: {
      initialIsBlockedBy: {
        type: Boolean,
        default: false,
      },
      authorized: {
        type: Boolean,
        default: false,
      },
      endpoint: {
        type: String,
      },
    },
    data() {
      return {
        isBlockedBy: this.initialIsBlockedBy,
      }
    },
    computed: {
      buttonColor() {
        return this.isBlockedBy
          ? 'bg-danger text-white'
          : 'bg-white'
      },
      buttonText() {
        return this.isBlockedBy
          ? 'ブロック中'
          : 'ブロックする'
      },
    },
    methods: {
      clickBlock() {
        if (!this.authorized) {
          alert('ブロック機能はログイン中のみ使用できます')
          return
        }

        this.isBlockedBy
          ? this.unblock()
          : this.block()
      },
      async block() {
        const response = await axios.put(this.endpoint)

        this.isBlockedBy = true
        location.reload();
      },
      async unblock() {
        const response = await axios.delete(this.endpoint)

        this.isBlockedBy = false
        location.reload();
      },
    },
  }
</script>