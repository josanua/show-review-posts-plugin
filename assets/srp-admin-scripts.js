document.addEventListener('DOMContentLoaded', function (event) {
  // jQuery
  jQuery(document).ready(function ($) {

    // console.log('este');

    // function for generating random Id
    function randomStringId() {
      let chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
      let string_length = 12;
      let randomstring = '';

      for (var i = 0; i < string_length; i++) {
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars.substring(rnum,rnum+1);
      }

      return randomstring;
    }

    $('#btn-generate-security-code').on('click', function (e) {

      let randomId = randomStringId();

      $('#srp_sync_security_code').val(randomId);

    });
  }); // end jQuery document ready
})