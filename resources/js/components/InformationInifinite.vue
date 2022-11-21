<template>
  <div>
    <div v-for="data in information" :key="data.id">
      <a :href=data.url>
        <div class="d-flex" style="color: black;">
          <p class="mr-3" style="white-space: nowrap;">{{ data.created_at.substr( 0, 4) }}年{{ data.created_at.substr( 5, 2) }}月{{ data.created_at.substr( 8, 2) }}日</p>
          <p>{{ data.title }}</p>
        </div>
      </a>
    </div>
    <infinite-loading @infinite="fetchInformation"></infinite-loading>
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
        information: [], // ツイートを格納
        value: {},
      }
    },
    methods: {
        fetchInformation($state) {
            let fetchedInformationList = this.fetchedInformationList(); // すでに取得したツイートのIDリストを取得

            axios.get('/fetchInformation', {
                params: {
                    fetchedInformationList: JSON.stringify(fetchedInformationList),
                    page: this.page,
                }
            })
            .then(response => {
                if (response.data.information.length) {
                  console.log(response)
                    this.page++;
                    response.data.information.forEach (value => {
                      value.url = '/news/' + value.id
                      this.information.push(value);
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

        fetchedInformationList() {
            let fetchedInformationList = [];
            for (let i = 0; i < this.information.length; i++) {
                fetchedInformationList.push(this.information[i].id);
            }
            return fetchedInformationList;
        }
    }
  }
</script>

<style type="text/css">

</style>