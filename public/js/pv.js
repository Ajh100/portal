function PVTrack() {
    var d = document;
    this.car = 0;
    this.brand = 0;
    this.series = 0;
    this.level = 0;
    this.price = 0;
	this.city = 0;
    this.init = function() {
        this.src = "http://api.taoche8.com/pv/?";
        this.src += this.car > 0 ? "&car=" + this.car: "";
        this.src += this.brand > 0 ? "&brand=" + this.brand: "";
		this.src += this.series > 0 ? "&series=" + this.series: "";
		this.src += this.level > 0 ? "&level=" + this.level: "";
		this.src += this.price > 0 ? "&price=" + this.price: "";
		this.src += this.city > 0 ? "&city=" + this.city: "";
    }
    this.send = function() {
        var c = new Image(1, 1);
        c.onLoad = function() {};
        c.src = this.src;
    }
    this.track = function() {
        this.init();
        this.send();
    }
}
