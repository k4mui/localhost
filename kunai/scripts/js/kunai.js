var addEvent = function(object, type, callback) {
  if (object == null || typeof(object) == 'undefined') return;
  if (object.addEventListener) {
      object.addEventListener(type, callback, false);
  } else if (object.attachEvent) {
      object.attachEvent('on' + type, callback);
  } else {
      object['on'+type] = callback;
  }
};

function _sizing(event) {
  $('body').height($(window).height());
  $('body').width($(window).width());
}
function _keydown(event) {
  //high($('#input').val());
}

function set_val(k, v) {
  if (typeof(Storage) !== "undefined") {
    // Store
    localStorage.setItem(k, v);
    // Retrieve
    //document.getElementById("result").innerHTML = localStorage.getItem("lastname");
  } else {
    console.log("Sorry, your browser does not support Web Storage...");
  }
}

function random_quote() {
  $.getJSON('data/json/quotes.json', (result) => {
    var authors = Object.keys(result);
    var author = authors[Math.floor(Math.random()*authors.length)];
    var quote = result[author][Math.floor(Math.random()*result[author].length)];
    $('#input').attr('placeholder', quote);
  });
}


$(document).ready(() => {
  addEvent(window, 'resize', _sizing);
  $('img#eye').hover(() => {
    $('img#eye').css("transform", "rotate(720deg)");
    $('img#eye').attr("src", "images/ims-1.png");
    $('img#eye').css("transform", "rotate(360deg)");
  }, () => {
    $('img#eye').css("transform", "rotate(-360deg)");
    $('img#eye').attr("src", "images/Sharingan.png");
    $('img#eye').css("transform", "rotate(-720deg)");
  });
});
