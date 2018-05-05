$(function(){
  $("[data-doughnut]").each(function(){
    var $this = $(this),
        start = 0,
        offset = 0,
        total = 0,
        n = 0;


    $this.find("> div").each(function(){
      var item = $(this);
      var value = item.data("doughnut-value") * 1;
      item.append('<div class="before"></div>');

      if(value > 50){
        item.addClass("big");
      }

      total += value;
      n++;
    }).each(function(index, el){
      var item = $(this);
      var value = Math.round(item.data("doughnut-value") * 3.6); //because the value is percent

      if(total >= 99.9 && n == index + 1){
        value = 360 - start;
      }

      item.css({
        '-webkit-transform': 'rotate(' + (start+offset) + 'deg)',
        '-moz-transform': 'rotate(' + (start+offset) + 'deg)',
        '-o-transform': 'rotate(' + (start+offset) + 'deg)',
        'transform': 'rotate(' + (start+offset) + 'deg)'
      });

      item.find('.before').css({
        '-webkit-transform': 'rotate(' + (value+1) + 'deg)',
        '-moz-transform': 'rotate(' + (value+1) + 'deg)',
        '-o-transform': 'rotate(' + (value+1) + 'deg)',
        'transform': 'rotate(' + (value+1) + 'deg)'
      });

      start += value;
    });
  });
});
