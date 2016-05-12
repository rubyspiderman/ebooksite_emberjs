ESF.CommentSerializer = ESF.ApplicationSerializer.extend(DS.EmbeddedRecordsMixin, {
  attrs: {
    user: {
      serialize: 'ids',
      deserialize: 'ids'
    },
    page: {
      serialize: 'ids',
      deserialize: 'ids'
    },
    photos: {
      embedded: 'always'
    }
  }
});
