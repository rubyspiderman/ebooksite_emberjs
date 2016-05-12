ESF.PageSerializer = ESF.ApplicationSerializer.extend(DS.EmbeddedRecordsMixin, {
  attrs: {
    user: {
      serialize: 'ids',
      deserialize: 'records'
    },
    school: {
      serialize: false,
      deserialize: 'records'
    },
    instructors: {
      serialize: 'ids',
      deserialize: 'records'
    },
    photos: {
      embedded: 'always'
    },
    videos: {
      embedded: 'always'
    },
    logs: {
      embedded: 'always'
    },
    comments: {
      serialize: false,
      deserialize: 'records'
    }
  }
});
