class Dane {
    static maxLevel = 0;
    constructor(path, name, id, level) {
        this.path = path;
        this.name = name;
        this.id = id;
        this.level = level;
        if (Dane.maxLevel < level) {
            Dane.maxLevel = level;
        }
    }
}
  var wynik= new Array();
  var i=0;