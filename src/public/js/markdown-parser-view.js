(function () {
  $(document).ready(function () {
    var converter = new showdown.Converter();
    var mdDest = document.querySelector('#markdown-content');

    var output = converter.makeHtml(mdDest.innerHTML);
    mdDest.innerHTML = output;
  });
}());
