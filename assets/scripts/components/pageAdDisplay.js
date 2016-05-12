ESF.PageAdDisplayComponent = Ember.Component.extend({
  classNames: ['ad'],
  templateName: 'components/ad',

  didInsertElement: function() {
    var cb = Math.floor(Math.random() * 999999);

    this.set('link', 'http://ad.spice-agenceweb.com/www/delivery/ck.php?n=a63c3fbd&cb=' + cb + '&esfid=' + this.get('esf'));
    this.set('image', 'http://ad.spice-agenceweb.com/www/delivery/avw.php?zoneid=14&cb=' + cb + '&n=a63c3fbd&esfid=' + this.get('esf'));
  }
});
