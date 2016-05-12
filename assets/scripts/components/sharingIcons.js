ESF.SharingIconsComponent = Ember.Component.extend({
  classNames: ['sharing'],
  templateName: 'components/sharing',

  didInsertElement: function() {
    var link = encodeURIComponent('http://www.esfbook.net/page/' + this.get('page'));
    var title = encodeURIComponent(this.get('subject'));

    this.set('email', 'mailto:?subject=' + title + '&body=' + link);
    this.set('facebook', 'http://www.facebook.com/sharer/sharer.php?s=100&p[url]=' + link);
    this.set('twitter', 'http://twitter.com/home?status=' + link);
    this.set('linkedin', 'http://www.linkedin.com/shareArticle?mini=true&url=' + link);
    this.set('plus', 'https://plus.google.com/share?url=' + link);
  }
});
