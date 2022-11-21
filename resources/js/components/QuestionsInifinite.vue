<template>
  <div>
    <div id="QandA-1">
      <dl>
        <div v-for="question in questions" :key="question.id" :id="question.id">
          <dt v-html="question.question"></dt>
          <dd v-html="question.answer"></dd>
        </div>
      </dl>
    </div>
    <infinite-loading @infinite="fetchQuestions"></infinite-loading>
  </div>
</template>

<script>
  import InfiniteLoading from 'vue-infinite-loading'
  export default {
    components: {
        InfiniteLoading,
    },
    data() {
      return {
        page: 0, // ツイートテーブルのOffsetを指定するための変数
        questions: [], // ツイートを格納
        value: {},
      }
    },
    methods: {
        fetchQuestions($state) {
            let fetchedQuestionsList = this.fetchedQuestionsList(); // すでに取得したツイートのIDリストを取得

            axios.get('/fetchQuestions', {
                params: {
                    fetchedQuestionsList: JSON.stringify(fetchedQuestionsList),
                    page: this.page,
                }
            })
            .then(response => {
                if (response.data.questions.length) {
                  console.log(response)
                    this.page++;
                    response.data.questions.forEach (value => {
                        this.questions.push(value);
                    });
                    $state.loaded();
                } else {
                    $state.complete();
                }
            })
            .catch(error => {
                console.log(error);
            })

        },

        fetchedQuestionsList() {
            let fetchedQuestionsList = [];
            for (let i = 0; i < this.questions.length; i++) {
                fetchedQuestionsList.push(this.questions[i].id);
            }
            return fetchedQuestionsList;
        }
    }
  }
</script>

<style type="text/css" scoped>
#QandA-1 {
	width: 100%;
	font-family: メイリオ;
	font-size: 14px; /*全体のフォントサイズ*/
}
#QandA-1 h2 {

}
#QandA-1 dt {
	background: #444; /* 「Q」タイトルの背景色 */
	color: #fff; /* 「Q」タイトルの文字色 */
	padding: 8px;
	border-radius: 2px;
}
#QandA-1 dt:before {
	content: "Q.";
	font-weight: bold;
	margin-right: 8px;
}
#QandA-1 dd {
	margin: 24px 16px 40px 32px;
	line-height: 140%;
	text-indent: -24px;
}
#QandA-1 dd:before {
	content: "A.";
	font-weight: bold;
	margin-right: 8px;
}

</style>