/*
 * Custom js for Expat 2 Expat Marketplace
 * Currency format requires numeral.js
 */

/* TODO: format the price input to currency using numeral.js
$("#price").keyup(function() {
	numeral($("#price").val()).format('0,0');
});
*/

/* Combining the currency, price, and unit into price per unit */
$(".ui-grid-b").bind('change keyup', function(){
	$("#priceunit").val($("#currency").val() + " " + numeral($("#price").val()).format('0,0') + "/" + $("#unit").val());
});