ESF.InstructorAdDisplayComponent = Ember.Component.extend({
  classNames: ['ad'],
  templateName: 'components/ad',

  didInsertElement: function() {
    var cb = Math.floor(Math.random() * 999999);

    this.set('link', 'http://ad.spice-agenceweb.com/www/delivery/ck.php?n=ab183d95&cb=' + cb);
    this.set('image', 'http://ad.spice-agenceweb.com/www/delivery/avw.php?zoneid=15&cb=' + cb + '&n=ab183d95');
  }
});
