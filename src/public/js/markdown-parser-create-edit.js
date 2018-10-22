(function () {
  $(document).ready(function () {
    var converter = new showdown.Converter();
    var mdDest = document.querySelector('#markdown-preview-dest');
    var mdSrc = document.querySelector('#content');

    var output = converter.makeHtml(mdSrc.value);
    mdDest.innerHTML = output;

    mdSrc.addEventListener('keydown', function (event) {
      var currKey = '';
      if (event.keyCode >= 65 && event.keyCode <= 90) {
        currKey = event.key;
      }

      var output = converter.makeHtml(this.value + currKey);
      mdDest.innerHTML = output;
    });
  });
}());
