#### css
```
<style>

	/* write your CSS code here */
	.wrapper{
		background-color: #F5F5F5;
		padding: 8px;
		height: 60vh;
		overflow: hidden;
		border-bottom: solid 8px #F5F5F5;
		margin-top: 0px !important;
		font-family:jost;
	}
	
	.card{
		background-color: #fff;
		padding: 8px;
		margin: 0 0 8px 0;
		color: #525252;
		font-family:jost;
	}
	
	.header{
		font-weight: 500;
		font-size: 18px;
		color: #111111;
	}
	
	.amount{
		font-family:jost;
		font-weight: 500;
		font-size: 16px;
		color: #767676;
	}
	.dark-font{
		font-weight: 500;
		color: #111111;
		font-size: 12px;
		font-family: jost;
	}
	
	.date-fields{
		display: flex;
	}

</style>

```

#### jquery
```
<script>

	/* write your JavaScript code here */
	jQuery(function(){
  var tickerLength = jQuery('.card').length;
  var tickerHeight = jQuery('.card').outerHeight();
  jQuery('.card:last-child').prependTo('.wrapper');
  jQuery('.wrapper').css('marginTop',-tickerHeight);
  function moveTop(){
    jQuery('.wrapper').animate({
      top : -tickerHeight
    },600, function(){
     jQuery('.card:first-child').appendTo('.wrapper');
      jQuery('.wrapper').css('top','');
    });
   }
  setInterval( function(){
    moveTop();
  }, 5000);
  });

</script>

```