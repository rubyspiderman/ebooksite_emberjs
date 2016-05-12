ESF.UserSerializer = ESF.ApplicationSerializer.extend(DS.EmbeddedRecordsMixin, {
  attrs: {
    instructors: {
      serialize: 'ids',
      deserialize: 'ids'
    },
    school: {
      serialize: 'ids',
      deserialize: 'ids'
    },
    picture: {
      embedded: 'always'
    },
    pages: {
      serialize: false,
      deserialize: 'ids'
    }
  }
});
