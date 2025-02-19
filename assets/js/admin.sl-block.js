function Scale_Lite_Copy_To_Clipboard(containerid) {
  if (document.selection) {
    var range = document.body.createTextRange();
    range.moveToElementText(document.getElementById(containerid));
    range.select().createTextRange();
    document.execCommand("Copy");
  } else if (window.getSelection) {
    window.getSelection().removeAllRanges()
    var range = document.createRange();
    range.selectNode(document.getElementById(containerid));
    window.getSelection().addRange(range);
    document.execCommand("Copy");
  }
}
