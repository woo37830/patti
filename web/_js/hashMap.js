function HashMap() {
  var obj = [];
  function find(key) {
    var i = obj.length;
    while (i--) {
      var curr = obj[i];
      if (curr[0] === key) {
        return i;
      }
    }
    return null;
  }
  var d = function dictionary(key, value) {
    var index = find(key);
    if (value) {
      if (index !== null) {
        obj.splice(index, 1);
      }
      obj.push([key, value]);
    } else {
      if (index !== null) {
        return obj[index][1];
      }
    }
  };
  d.get = function (key) {
    return obj[find(key)][1];
  };
  d.size = function () {
    return obj.length;
  };
  d.delete = function (key) {
    obj.splice(find(key), 1);
  };
  d.each = function (func) {
    for (var i = 0; i < obj.length; i++) {
      var item = obj[i];
      func(item[0], item[1]);
    }
  };
  return d;
}
