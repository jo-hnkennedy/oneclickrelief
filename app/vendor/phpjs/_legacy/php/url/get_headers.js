module.exports = function get_headers (url, format) { // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/get_headers/
  // original by: Paulo Freitas
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //        note: This function uses XmlHttpRequest and cannot retrieve resource from different domain.
  //        note: Synchronous so may lock up browser, mainly here for study purposes.
  //        test: skip-all
  //   example 1: get_headers('http://kvz.io/pj_test_supportfile_1.htm')[0]
  //   returns 1: 'Date: Wed, 13 May 2009 23:53:11 GMT'

  var array_filter = require('../array/array_filter')
  var req = this.window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest()

  if (!req) {
    throw new Error('XMLHttpRequest not supported')
  }
  var tmp, headers, pair, i, j = 0

  req.open('HEAD', url, false)
  req.send(null)

  if (req.readyState < 3) {
    return false
  }

  tmp = req.getAllResponseHeaders()
  tmp = tmp.split('\n')
  tmp = array_filter(tmp, function (value) {
    return value.substring(1) !== ''
  })
  headers = format ? {} : []

  for (var i in tmp) {
    if (format) {
      pair = tmp[i].split(':')
      headers[pair.splice(0, 1)] = pair.join(':')
        .substring(1)
    } else {
      headers[j++] = tmp[i]
    }
  }

  return headers
}
