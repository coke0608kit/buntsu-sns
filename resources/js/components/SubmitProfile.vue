<template>
  <div class="main mt-5">
    <p v-if="this.error != ''" style="color:red">{{ this.error }}</p>
    <p class="btn btn-primary w-100" @click="submit">更新する</p>
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
        error: '',
        fileExtension: '',
        arrayHobbies: [],
        awsfilename: '',
        registerNickname: '',
        registerYear: '',
        registerMonth: '',
        registerDay: '',
        registerGender: '',
        registerZipcode1: '',
        registerZipcode2: '',
        registerPref: '',
        registerAddress1: '',
        registerAddress2: '',
        registerRealname: '',
        registerProfile: '',
        registerCanSendGender: '',
        registerStatus: '',
        registerCondition: ''
      }
    },
  methods: {
    submit() {
        console.log('startttttttttttt')
      document.getElementById('form').style.display = "none"
      document.getElementsByClassName('loading')[0].style.display = "block"
      this.error = ''
      this.arrayTags = []
      let tags = document.getElementsByName('tags')
      let regex = new RegExp(/[!"#$%&'()\*\+\-\.,\/:;<=>?@\[\\\]^_`{|}~]/g);
      JSON.parse(tags[0].value).forEach(element => {
        if (regex.test(element.text)) {
          this.error = 'タグに記号を使わないでください。';
        } else {
          this.arrayHobbies.push(element.text);
        }
      });
      this.registerNickname = document.getElementsByName('nickname')[0].value;
        console.log(this.registerNickname)
      this.registerYear = document.getElementsByName('year')[0].value;
      this.registerMonth = document.getElementsByName('month')[0].value;
      this.registerDay = document.getElementsByName('day')[0].value;
        console.log('年月日通貨')
      let genders = document.getElementsByName('gender');
      genders.forEach(element => {
        if (element.checked) {
          this.registerGender = element.value;
        }
      })
        console.log('性別')
      if (!document.getElementsByName('zipcode1')[0]) {
        this.registerZipcode1 = ''
        this.registerZipcode2 = ''
        this.registerPref = ''
        this.registerAddress1 = ''
        this.registerAddress2 = ''
      } else {
        this.registerZipcode1 = document.getElementsByName('zipcode1')[0].value
        this.registerZipcode2 = document.getElementsByName('zipcode2')[0].value
        this.registerPref = document.getElementsByName('pref')[0].value
        this.registerAddress1 = document.getElementsByName('address1')[0].value
        this.registerAddress2 = document.getElementsByName('address2')[0].value
      }
        console.log('住所')
      this.registerRealname = document.getElementsByName('realname')[0].value
      this.registerProfile = document.getElementsByName('profile')[0].value
        console.log('プロフィール')
      let canSendGenders = document.getElementsByName('canSendGender');
      canSendGenders.forEach(element => {
        if (element.checked) {
          this.registerCanSendGender = element.value;
        }
      })
        console.log('canSendGender')
      let someStatus = document.getElementsByName('status');
      someStatus.forEach(element => {
        if (element.checked) {
          this.registerStatus = element.value;
        }
      })
      let someCondition = document.getElementsByName('condition');
      someCondition.forEach(element => {
        if (element.checked) {
          this.registerCondition = element.value;
        }
      })


        console.log('imageStartttttttt')
      let imageBase64 = document.getElementsByName('imageBase64')[0].value;
      if (imageBase64.indexOf( 'https' ) !== -1) {
        //画像の変更なし
        this.awsfilename = 'none'
        console.log('submitStart')
        this.submitProfile();
      } else {
        //画像の変更有
        this.fileExtension = imageBase64.toString().slice(imageBase64.indexOf('/') + 1, imageBase64.indexOf(';'))

        axios.post('/profile/generateURL', {
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
            this.submitProfile();
          })
          .catch(error => {
            console.log(error)
              document.getElementsByClassName('message')[0].textContent = '異常が発生しました。プロフィールの更新をやり直してください'
              document.getElementsByClassName('message')[0].style.display = "block"
              window.setTimeout(function(){
                  location.reload()
              }, 1000);
          })
        })
        .catch(error => {
          console.log(error)
              document.getElementsByClassName('message')[0].textContent = '異常が発生しました。プロフィールの更新をやり直してください'
              document.getElementsByClassName('message')[0].style.display = "block"
              window.setTimeout(function(){
                  location.reload()
              }, 1000);
        })
      }
    },
    submitProfile() {
      axios.post('/setting/profile', {
        filename: this.awsfilename,
        hobbies: this.arrayHobbies,
        id: this.authid,
        nickname: this.registerNickname,
        year: this.registerYear,
        month: this.registerMonth,
        day: this.registerDay,
        gender: this.registerGender,
        zipcode1: this.registerZipcode1,
        zipcode2: this.registerZipcode2,
        pref: this.registerPref,
        address1: this.registerAddress1,
        address2: this.registerAddress2,
        realname: this.registerRealname,
        profile: this.registerProfile,
        canSendGender: this.registerCanSendGender,
        status: this.registerStatus,
        condition: this.registerCondition
      })
      .then(response => {
        if (response.data == 'success'){
          document.getElementsByClassName('loading')[0].style.display = "none"
          document.getElementsByClassName('message')[0].textContent = '更新完了！HOMEに戻ります。'
          document.getElementsByClassName('message')[0].style.display = "block"
          window.setTimeout(function(){
              window.location.href = '/home'
          }, 1000);
        } else {
          document.getElementsByClassName('message')[0].textContent = '異常が発生しました。プロフィールの更新をやり直してください'
          document.getElementsByClassName('message')[0].style.display = "block"
          window.setTimeout(function(){
              location.reload()
          }, 1000);
        }
      })
      .catch(error => {
        console.log(error)
          document.getElementsByClassName('message')[0].textContent = '異常が発生しました。プロフィールの更新をやり直してください'
          document.getElementsByClassName('message')[0].style.display = "block"
          window.setTimeout(function(){
              location.reload()
          }, 1000);
      })
    }
  }
}
</script>