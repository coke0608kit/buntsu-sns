<template>
  <div class="main">
    <p v-if="this.error1 != ''" style="color:red">{{ this.error1 }}</p>
    <p v-if="this.error2 != ''" style="color:red">{{ this.error2 }}</p>
    <p class="btn btn-primary w-100" @click="submit">投稿する</p>
  </div>
</template>

<script>
  export default {
    props: {
      authid: {
        type: String,
        default: '',
      },
    },
    data() {
      return {
        error1: '',
        error2: '',
        fileExtension: '',
        arrayTags: [],
        awsfilename: ''
      }
    },
  methods: {
    submit() {
        document.getElementsByClassName('card-text')[0].style.display = "none"
        document.getElementsByClassName('loading')[0].style.display = "block"
        this.error1 = ''
        this.error2 = ''
        this.arrayTags = []
        let tags = document.getElementsByName('tags')
        var regex = new RegExp(/[!"#$%&'()\*\+\-\.,\/:;<=>?@\[\\\]^_`{|}~]/g);
        JSON.parse(tags[0].value).forEach(element => {
          if (regex.test(element.text)) {
            this.error2 = 'タグに記号を使わないでください。'
            document.getElementsByClassName('card-text')[0].style.display = "block"
            document.getElementsByClassName('loading')[0].style.display = "none"
            return;
          } else {
            this.arrayTags.push(element.text);
          }
        });
      let imageBase64 = document.getElementsByName('imageBase64')[0].value;
      if (imageBase64 == '') {
        this.error1 = '画像は必須です。'
        document.getElementsByClassName('card-text')[0].style.display = "block"
        document.getElementsByClassName('loading')[0].style.display = "none"
      } else {
        this.fileExtension = imageBase64.toString().slice(imageBase64.indexOf('/') + 1, imageBase64.indexOf(';'))

        axios.post('/articles/generateURL', {
            params: {
                id: this.authid,
                ext: this.fileExtension,
            }
        })
        .then(response => {
          var filename = 'test.' + this.fileExtension;
          var arr = imageBase64.split(','),
              mime = arr[0].match(/:(.*?);/)[1],
              bstr = atob(arr[1]), 
              n = bstr.length, 
              u8arr = new Uint8Array(n);
              
          while(n--){
              u8arr[n] = bstr.charCodeAt(n);
          }
          this.awsfilename = response.data[1]
          
          var image = new File([u8arr], filename, {type:mime});
          axios({
              method: 'PUT',
              url: response.data[0],
              headers: {
                  'Content-Type': 'image/'+this.fileExtension,
              },
              data: image 
          }).then(response => {
            axios.post('/articles', {
              filename: this.awsfilename,
              tags: this.arrayTags,
              id: this.authid,
            })
            .then(response => {
              if (response.data == 'success'){
                document.getElementsByClassName('loading')[0].style.display = "none"
                document.getElementsByClassName('message')[0].textContent = '投稿完了！HOMEに戻ります。'
                document.getElementsByClassName('message')[0].style.display = "block"
                window.setTimeout(function(){
                    window.location.href = '/home'
                }, 1000);
              } else {
                document.getElementsByClassName('message')[0].textContent = '異常が発生しました。再度投稿をやり直してください'
                document.getElementsByClassName('message')[0].style.display = "block"
                window.setTimeout(function(){
                    location.reload()
                }, 1000);
              }
            })
            .catch(error => {
              console.log(error)
                document.getElementsByClassName('message')[0].textContent = '異常が発生しました。再度投稿をやり直してください'
                document.getElementsByClassName('message')[0].style.display = "block"
                window.setTimeout(function(){
                    location.reload()
                }, 1000);
            })
          })
          .catch(error => {
            console.log(error)
              document.getElementsByClassName('message')[0].textContent = '異常が発生しました。再度投稿をやり直してください'
              document.getElementsByClassName('message')[0].style.display = "block"
              window.setTimeout(function(){
                  location.reload()
              }, 1000);
          })
        })
        .catch(error => {
          console.log(error)
              document.getElementsByClassName('message')[0].textContent = '異常が発生しました。再度投稿をやり直してください'
              document.getElementsByClassName('message')[0].style.display = "block"
              window.setTimeout(function(){
                  location.reload()
              }, 1000);
        })
      }
    },
  }
}
</script>