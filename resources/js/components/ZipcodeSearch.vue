<template>
  <div class="main">
    <test-pre-qutte-reader
      v-if="this.postalCode1 == null || this.postalCode2 == null  || this.address1 == null  || this.address2 == null || this.pref == null "
      class="mt-4 mb-1"
      @judge="reflect"
      @status="modal"
    >
    </test-pre-qutte-reader>
    <div v-if="(this.closeJudge != 'error' && this.modalJudge == 'true') || (this.postalCode1 != null && this.postalCode2 != null  && this.address1 != null  && this.address2 != null && this.pref != null)">
      <div class="address">
        <p class="mb-0">郵便番号</p>
        <div class="flex-row mb-1">
          <input type="text" class="w-25" v-model="postalCode1" name="zipcode1" />
          <span>-</span>
          <input type="text" class="w-25" v-model="postalCode2" name="zipcode2" />
          <button @click="yubinbango()" type="button">住所検索</button>
        </div>
      </div>
      <div class="result">
        <input type="hidden" class="w-100 mb-1" v-model="pref" name="pref" readonly/>
        <p class="mb-0">住所1</p>
        <input type="text" class="w-100 mb-1" v-model="address1" name="address1" readonly/>
        <p class="mb-0">住所2</p>
        <input type="text" class="w-100 mb-1" v-model="address2" name="address2" placeholder="住所1の続きを入力してください" />
      </div>
    </div>
  </div>
</template>

<script>
  import TestPreQutteReader from './TestPreQutteReader'
  import { Core as YubinBangoCore } from "yubinbango-core2";
  export default {
    components: {
        TestPreQutteReader,
    },
    props: {
      setpref: {
        type: String,
        default: '',
      },
      setzipcode1: {
        type: String,
        default: '',
      },
      setzipcode2: {
        type: String,
        default: '',
      },
      setaddress1: {
        type: String,
        default: '',
      },
      setaddress2: {
        type: String,
        default: '',
      }
    },
    data() {
      return {
        postalCode1: JSON.parse(this.setzipcode1),
        postalCode2: JSON.parse(this.setzipcode2),
        postalCode: null,
        pref: JSON.parse(this.setpref),
        address1: JSON.parse(this.setaddress1),
        address2: JSON.parse(this.setaddress2),
        closeJudge: '',
        modalJudge: ''
      }
    },
  methods: {
    yubinbango() {
      this.postalCode = this.postalCode1 + this.postalCode2;
      new YubinBangoCore(this.postalCode, (addr)=> {
        this.address1 = addr.region // 都道府県
        this.address1 += addr.locality // 市区町村
        this.address1 += addr.street // 町域
        this.pref = addr.region
      })
    },
    reflect(value) {
      this.closeJudge = value
    },
    modal(value) {
      this.modalJudge = value
    }
  }
}
</script>