<template>
  <div class="mt-2">
  <div class="card">
    <div class="card-body">
      <div class="card-text">
        <table class="table table-borderless">
          <tr><th class="text-center align-middle text-nowrap">都道府県</th><th>
            <select
              id="pref"
              class="w-100 px-4 py-2 border rounded"
              @change="fetchData"
            >
              <option value="all" selected>全国</option>
              <option
                :value="pref.id"
                :key="index"
                v-for="(pref, index) in prefList"
              >
                {{ pref.name }}
              </option>
            </select></th>
          </tr>
          <tr><th class="text-center align-middle">年齢</th><th style="height: 85px;vertical-align: middle;">
            <vue-slider v-model="age" tooltip="none" :process="process" :interval="10" :enable-cross="false" @change="fetchData">
                <template v-slot:process="{ start, end, style, index }">
                <div class="vue-slider-process" :style="style">
                    <div :class="[
                    'merge-tooltip',
                    'vue-slider-dot-tooltip-inner',
                    'vue-slider-dot-tooltip-inner-top',
                    ]">
                    {{ age[index] }} 才 ～ {{ age[index + 1] }} 才
                    </div>
                </div>
                </template>
            </vue-slider>
            </th>
          </tr>
          <tr><th class="text-center align-middle">性別</th><th>
            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
              <label class="btn text-nowrap" @click="gender(0)">
                <input type="checkbox" name="gender" id="option1" autocomplete="off" value="1"> 男性
              </label>
              <label class="btn text-nowrap" @click="gender(1)">
                <input type="checkbox" name="gender" id="option2" autocomplete="off" value="2"> 女性
              </label>
              <label class="btn text-nowrap" @click="gender(2)">
                <input type="checkbox" name="gender" id="option3" autocomplete="off" value="9"> 未回答
              </label>
            </div></th>
          </tr>
          <tr><th class="text-center align-middle">趣味</th><th>
            <input
              type="hidden"
              name="tags"
              :value="tagsJson"
            >
            <vue-tags-input
              v-model="tag"
              :tags="tags"
              placeholder="1タグまで検索できます"
              :add-on-key="[13, 32]"
              :autocomplete-items="filteredItems"
              @tags-changed="newTags => tags = newTags"
              :max-tags="1"
              :autocomplete-min-length="2"
            /></th>
          </tr>
          <tr><th class="text-center align-middle text-nowrap">キーワード</th><th>
            <input type="text" name="keyword" id="keyword" class="w-100" @change="fetchData">
            </th>
          </tr>
          <tr><th class="text-center align-middle text-nowrap">募集状況</th><th>
            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
              <label class="btn text-nowrap" @click="status(0)">
                <input type="checkbox" name="status" id="option1" autocomplete="off" value="false"> 募集停止
              </label>
              <label class="btn text-nowrap" @click="status(1)">
                <input type="checkbox" name="status" id="option2" autocomplete="off" value="true"> 募集中
              </label>
            </div></th>
          </tr>
          <tr><th class="text-center align-middle text-nowrap">頻度</th><th>
            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
              <label class="btn text-nowrap" @click="condition(0)">
                <input type="checkbox" name="condition" id="option1" autocomplete="off" value="false"> テキパキ
              </label>
              <label class="btn text-nowrap" @click="condition(1)">
                <input type="checkbox" name="condition" id="option2" autocomplete="off" value="true"> まったり
              </label>
            </div></th>
          </tr>
          <tr><th class="text-center align-middle text-nowrap">プラン</th><th>
            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
              <label class="btn text-nowrap" @click="plan(0)">
                <input type="checkbox" name="plan" id="option1" autocomplete="off" value="free"> フリー
              </label>
              <label class="btn text-nowrap" @click="plan(1)">
                <input type="checkbox" name="plan" id="option2" autocomplete="off" value="lite"> ライト
              </label>
              <label class="btn text-nowrap" @click="plan(2)">
                <input type="checkbox" name="plan" id="option2" autocomplete="off" value="standard"> スタンダード
              </label>
            </div></th>
          </tr>
        </table>
        <p class="text-right mt-4 mx-0 mb-0" >検索結果：　{{ currentAllUsersCount }}名</p>
      </div>
    </div>
  </div>
  <div v-show="!this.searchStart">
      <div class="card w-100 mt-4" v-if="this.authorizedCheck">
        <div class="card-body">
          <h5 class="card-title">文通相手は見つかりましたか？</h5>
          <div class="card-text">
            ランダムな相手と文通が可能です。ランダムな相手の決め方は、プログラムがやりとり可能な性別やブロック判定を考慮しランダムで1人を割り当てます。選ばれた相手が嫌な場合でも、もう一度読み込ませることで相手を変えることができます。
            <div class="d-flex align-items-center mt-2">
              <bottle-pre-qutte-reader
                :from=this.authorizedId
                >
              </bottle-pre-qutte-reader>
              <p class="mb-0 ml-2">左のQRボタンからQ手を読み込ませることでランダムな相手を決めることができます。</p>
            </div>
          </div>
        </div>
      </div>
    <div v-else class="mt-4">
      <div class="card w-100">
        <div class="card-body">
          <h5 class="card-title">文通相手を見つけたら</h5>
          <p class="card-text">会員登録してみませんか？BUNTSUは無料でもフォローや画像の投稿ができます。住所を登録していれば、手紙を受け取ることもできます。月額課金をしなくてもQ手を単品で購入すれば、手紙を送ることも可能です。</p>
          <a href="/register" class="card-link">会員登録</a>
          <a href="/login" class="card-link">ログイン</a>
        </div>
      </div>
    </div>

  </div>
  
  <div v-show="!this.wait">
    <search-user-inifinite
      v-show="this.searchStart"
      page-type='search'
     :authorized-id=this.authorizedId
     :authorized-check=this.authorizedCheck
     :pref=this.sendPref
     :age1=this.age[0]
     :age2=this.age[1]
     :male=this.sendMale
     :female=this.sendFemle
     :none=this.sendNone
     :status1=this.sendStatus1
     :status2=this.sendStatus2
     :condition1=this.sendCondition1
     :condition2=this.sendCondition2
     :keyword=this.sendKeyword
     :hobbies=this.jsontags
     :check=this.check
     :plan1=this.sendPlan1
     :plan2=this.sendPlan2
     :plan3=this.sendPlan3
     @counter="reflectNum"
     >
    </search-user-inifinite>
  </div>
    <div v-if="this.wait" class="loader">
    </div>
  </div>
</template>

<script>
  import VueTagsInput from '@johmun/vue-tags-input';
  import SearchUserInifinite from './SearchUserInifinite';
  import BottlePreQutteReader from './BottlePreQutteReader';
  import VueSlider from 'vue-slider-component'
  import 'vue-slider-component/theme/default.css'

  export default {
    components: {
      VueTagsInput,
      SearchUserInifinite,
      VueSlider,
      BottlePreQutteReader
    },
    props: {
      authorizedId: {
        type: String,
        default: 0,
      },
      authorizedCheck: {
        type: Boolean,
        default: false,
      },
      autocompleteItems: {
        type: Array,
        default: [],
      },
    },
    data() {
      return {
        searchStart: false,
        currentAllUsersCount: 0,
        tag: '',
        age: [0, 100],
        process: val => [
            [val[0], val[1]],
        ],
        tags: [],
        jsontags: '',
        check: 0,
        prefList: [
          { id: "北海道", name: "北海道" },
          { id: "青森県", name: "青森県" },
          { id: "岩手県", name: "岩手県" },
          { id: "宮城県", name: "宮城県" },
          { id: "秋田県", name: "秋田県" },
          { id: "山形県", name: "山形県" },
          { id: "福島県", name: "福島県" },
          { id: "茨城県", name: "茨城県" },
          { id: "栃木県", name: "栃木県" },
          { id: "群馬県", name: "群馬県" },
          { id: "埼玉県", name: "埼玉県" },
          { id: "千葉県", name: "千葉県" },
          { id: "東京都", name: "東京都" },
          { id: "神奈川県", name: "神奈川県" },
          { id: "新潟県", name: "新潟県" },
          { id: "富山県", name: "富山県" },
          { id: "石川県", name: "石川県" },
          { id: "福井県", name: "福井県" },
          { id: "山梨県", name: "山梨県" },
          { id: "長野県", name: "長野県" },
          { id: "岐阜県", name: "岐阜県" },
          { id: "静岡県", name: "静岡県" },
          { id: "愛知県", name: "愛知県" },
          { id: "三重県", name: "三重県" },
          { id: "滋賀県", name: "滋賀県" },
          { id: "京都府", name: "京都府" },
          { id: "大阪府", name: "大阪府" },
          { id: "兵庫県", name: "兵庫県" },
          { id: "奈良県", name: "奈良県" },
          { id: "和歌山県", name: "和歌山県" },
          { id: "鳥取県", name: "鳥取県" },
          { id: "島根県", name: "島根県" },
          { id: "岡山県", name: "岡山県" },
          { id: "広島県", name: "広島県" },
          { id: "山口県", name: "山口県" },
          { id: "徳島県", name: "徳島県" },
          { id: "香川県", name: "香川県" },
          { id: "愛媛県", name: "愛媛県" },
          { id: "高知県", name: "高知県" },
          { id: "福岡県", name: "福岡県" },
          { id: "佐賀県", name: "佐賀県" },
          { id: "長崎県", name: "長崎県" },
          { id: "熊本県", name: "熊本県" },
          { id: "大分県", name: "大分県" },
          { id: "宮崎県", name: "宮崎県" },
          { id: "鹿児島県", name: "鹿児島県" },
          { id: "沖縄県", name: "沖縄県" },
        ],
        ageList: [
          { id: "1", name: "10代以下" },
          { id: "2", name: "20代" },
          { id: "3", name: "30代" },
          { id: "4", name: "40代" },
          { id: "5", name: "50代" },
          { id: "6", name: "60代" },
          { id: "7", name: "70代" },
          { id: "8", name: "80代" },
          { id: "9", name: "90代以上" },
        ],
        sendPref: '',
        sendMale: '',
        sendFemle: '',
        sendNone: '',
        sendStatus1: '',
        sendStatus2: '',
        sendCondition1: '',
        sendCondition2: '',
        sendKeyword: '',
        sendPlan1: '',
        sendPlan2: '',
        sendPlan3: '',
        wait: false,
      };
    },
    methods: {
      gender (type) {
        if (document.getElementsByName('gender')[type].checked) {
          document.getElementsByName('gender')[type].checked = false
        } else {
          document.getElementsByName('gender')[type].checked = true
        }
        this.fetchData()
      },
      status (type) {
        if (document.getElementsByName('status')[type].checked) {
          document.getElementsByName('status')[type].checked = false
        } else {
          document.getElementsByName('status')[type].checked = true
        }
        this.fetchData()
      },
      condition (type) {
        if (document.getElementsByName('condition')[type].checked) {
          document.getElementsByName('condition')[type].checked = false
        } else {
          document.getElementsByName('condition')[type].checked = true
        }
        this.fetchData()
      },
      plan (type) {
        if (document.getElementsByName('plan')[type].checked) {
          document.getElementsByName('plan')[type].checked = false
        } else {
          document.getElementsByName('plan')[type].checked = true
        }
        this.fetchData()
      },
      reflectNum (value) {
        this.currentAllUsersCount = value
        this.wait = false;
      },
      fetchData () {
        this.searchStart = true;
        this.sendPref = document.getElementById('pref').value;
        this.sendMale = document.getElementsByName('gender')[0].checked;
        this.sendFemle = document.getElementsByName('gender')[1].checked;
        this.sendNone = document.getElementsByName('gender')[2].checked;
        this.sendStatus1 = document.getElementsByName('status')[0].checked;
        this.sendStatus2 = document.getElementsByName('status')[1].checked;
        this.sendCondition1 = document.getElementsByName('condition')[0].checked;
        this.sendCondition2 = document.getElementsByName('condition')[1].checked;
        this.sendKeyword = document.getElementsByName('keyword')[0].value;
        this.sendPlan1 = document.getElementsByName('plan')[0].checked;
        this.sendPlan2 = document.getElementsByName('plan')[1].checked;
        this.sendPlan3 = document.getElementsByName('plan')[2].checked;
        this.check = this.check + 1;
        this.wait = true;
      }
    },
    computed: {
      filteredItems() {
        return this.autocompleteItems.filter(i => {
          return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
        });
      },
      tagsJson() {
        this.jsontags = JSON.stringify(this.tags).replace(/\(.+?\)/g, "");
        if (JSON.parse(this.jsontags)[0]) {
          this.fetchData()
        }
        return this.jsontags;
      },
    },
  };
</script>
<style lang="css" scoped>
  .vue-tags-input {
    max-width: inherit;
  }
  .table td, .table th {
    padding: 5px 0;
  }
  .table th {
    padding-right: 5px;
  }
  label.btn.waves-effect.waves-light.active {
    background-color: #ffc68e;
  }
  .btn {
    margin: 0!important;
    padding: 0.5rem 0;
  }
  .merge-tooltip {
    position: absolute;
    left: 50%;
    bottom: 100%;
    transform: translate(-50%, -15px);
  }
.loader {
  color: #6c6b70;
  font-size: 90px;
  text-indent: -9999em;
  overflow: hidden;
  width: 1em;
  height: 1em;
  border-radius: 50%;
  margin: 72px auto;
  position: relative;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-animation: load6 1.7s infinite ease, round 1.7s infinite ease;
  animation: load6 1.7s infinite ease, round 1.7s infinite ease;
}
@-webkit-keyframes load6 {
  0% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  5%,
  95% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  10%,
  59% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
  }
  20% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
  }
  38% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
  }
  100% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
}
@keyframes load6 {
  0% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  5%,
  95% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  10%,
  59% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
  }
  20% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
  }
  38% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
  }
  100% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
}
@-webkit-keyframes round {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes round {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
</style>
<style lang="css">
  .vue-tags-input .ti-tag {
    background: transparent;
    border: 1px solid #747373;
    color: #747373;
    margin-right: 4px;
    border-radius: 0px;
    font-size: 13px;
  }
  .vue-tags-input .ti-tag::before {
    content: "#";
  }
</style>
