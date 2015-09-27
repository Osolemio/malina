/**
 * Project: JSGadget
 * Gadget:  JSDisplay
 */

/**
 *
 * Copyright (c) 2014 Serge L. Ryadkow http://jsgadget.ru
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify,
 * merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies
 * or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 */

/**
 *
 * Versions history
 *
 * 1.1.1
 * First release
 *
 */

var JSGadget = JSGadget || {};

/**
 * display.js
 */

//Display declaration
JSGadget.Display = function(owner, options, val) {
  this.owner = typeof(owner) == "string" ? $(owner) : owner;
	this.owner.css(JSGadget.Display.Style.OWNER);
	this.opt = {
		digits: 1,
		color: "black",
		shadow: {
			color: null,
			dx: 4,
			dy: 4
		}
	};
	JSGadget.setopt(this.opt, options);
	this.preproc(val !== undefined ? val : "");
	this.resize();
};
//Display implementation
JSGadget.Display.prototype.resize = function() {
	this.owner.empty();
	this.size = {w: this.owner.width(), h: this.owner.height()};
	if (this.size.w > 0 && this.size.h > 0) {
		this.canv = this.owner.append("<canvas width='" +	this.size.w + "' height='" + this.size.h +
				"'/>").children().last().css({position: "absolute", left: 0, top: 0});
		this.ctx = this.canv[0].getContext("2d");
		this.ctx.lineCap = "round";
		this.draw();
	} else
		this.canv = this.ctx = null;
};
JSGadget.Display.prototype.setVal = function(val) {
	this.preproc(val);
	this.draw();
};
JSGadget.Display.prototype.clear = function() {
 	if (this.ctx)
 		this.ctx.clearRect(0, 0, this.size.w, this.size.h);
};
JSGadget.Display.prototype.draw = function() {
  if (this.ctx) {
 		this.clear();
 		this.ctx.save();
 		this.ctx.scale(this.size.w / 100 / this.opt.digits, this.size.h / 100);
 		if (this.opt.shadow.color) {
 			this.ctx.save();
	 	  this.ctx.translate(this.opt.shadow.dx, this.opt.shadow.dy);
	  	this.ctx.fillStyle = this.opt.shadow.color;
	  	this.draw_();
 	  	this.ctx.restore();
 		}
  	this.ctx.fillStyle = this.opt.color;
  	this.draw_();
  	this.ctx.restore();
  }
};
JSGadget.Display.prototype.draw_ = function() {
	for (var i = 0; i < this.opt.digits; ++i) {
		if (i)
	 	  this.ctx.translate(100, 0);
		this.drawD(this.data[i], this.point === i);
	}
};

/**
 * digit.js
 */

JSGadget.Display.prototype.drawD = function(d, dp) {
	switch (d) {
		case "0":
			this.drawS("a");
			this.drawS("b");
			this.drawS("c");
			this.drawS("d");
			this.drawS("e");
			this.drawS("f");
			break;
		case "1":
			this.drawS("b");
			this.drawS("c");
			break;
		case "2":
			this.drawS("a");
			this.drawS("b");
			this.drawS("d");
			this.drawS("e");
			this.drawS("g");
			break;
		case "3":
			this.drawS("a");
			this.drawS("b");
			this.drawS("c");
			this.drawS("d");
			this.drawS("g");
			break;
		case "4":
			this.drawS("b");
			this.drawS("c");
			this.drawS("f");
			this.drawS("g");
			break;
		case "5":
			this.drawS("a");
			this.drawS("c");
			this.drawS("d");
			this.drawS("f");
			this.drawS("g");
			break;
		case "6":
			this.drawS("a");
			this.drawS("c");
			this.drawS("d");
			this.drawS("e");
			this.drawS("f");
			this.drawS("g");
			break;
		case "7":
			this.drawS("a");
			this.drawS("b");
			this.drawS("c");
			break;
		case "8":
			this.drawS("a");
			this.drawS("b");
			this.drawS("c");
			this.drawS("d");
			this.drawS("e");
			this.drawS("f");
			this.drawS("g");
			break;
		case "9":
			this.drawS("a");
			this.drawS("b");
			this.drawS("c");
			this.drawS("d");
			this.drawS("f");
			this.drawS("g");
			break;
		case "-":
			this.drawS("g");
			break;
	}
	if (dp)
		this.drawS("h");
};
/**
 * segment.js
 */

JSGadget.Display.prototype.drawS = function(s) {
	this.ctx.beginPath();
	switch (s) {
		case "a":
			this.ctx.moveTo(28, 9);
			this.ctx.lineTo(33, 5);
			this.ctx.lineTo(78, 5);
			this.ctx.lineTo(81, 8);
			this.ctx.lineTo(76, 12);
			this.ctx.lineTo(32, 12);
			break;
		case "b":
			this.ctx.moveTo(84, 10);
			this.ctx.lineTo(86, 13);
			this.ctx.lineTo(80, 44);
			this.ctx.lineTo(77, 47);
			this.ctx.lineTo(71, 42);
			this.ctx.lineTo(76, 16);
			break;
		case "c":
			this.ctx.moveTo(77, 51);
			this.ctx.lineTo(79, 53);
			this.ctx.lineTo(73, 85);
			this.ctx.lineTo(70, 88);
			this.ctx.lineTo(64, 83);
			this.ctx.lineTo(69, 57);
			break;
		case "d":
			this.ctx.moveTo(67, 90);
			this.ctx.lineTo(62, 94);
			this.ctx.lineTo(17, 94);
			this.ctx.lineTo(14, 91);
			this.ctx.lineTo(18, 87);
			this.ctx.lineTo(63, 87);
			break;
		case "e":
			this.ctx.moveTo(11, 89);
			this.ctx.lineTo(9, 86);
			this.ctx.lineTo(15, 55);
			this.ctx.lineTo(18, 52);
			this.ctx.lineTo(24, 57);
			this.ctx.lineTo(19, 83);
			break;
		case "f":
			this.ctx.moveTo(18, 48);
			this.ctx.lineTo(16, 45);
			this.ctx.lineTo(22, 14);
			this.ctx.lineTo(25, 11);
			this.ctx.lineTo(31, 16);
			this.ctx.lineTo(26, 42);
			break;
		case "g":
			this.ctx.moveTo(21, 50);
			this.ctx.lineTo(25, 46);
			this.ctx.lineTo(70, 46);
			this.ctx.lineTo(74, 49);
			this.ctx.lineTo(69, 53);
			this.ctx.lineTo(24, 53);
			break;
		case "h":
			this.ctx.moveTo(84, 80);
			this.ctx.lineTo(92, 80);
			this.ctx.lineTo(90, 87);
			this.ctx.lineTo(78, 97);
			this.ctx.lineTo(76, 97);
			this.ctx.lineTo(82, 88);
			break;
	}
	this.ctx.closePath();
	this.ctx.fill();
};
/**
 * preproc.js
 */

JSGadget.Display.prototype.preproc = function(v) {
	this.data = [];
	this.point = undefined;
	for (var i = 0, l = (v += "").length; i < l; ++i)
		if (i && (v.charAt(i) == "." || v.charAt(i) == ","))
			this.point = i - 1;
		else
			this.data.push(v.charAt(i));
	var l = this.data.length - this.opt.digits;
	if (l > 0)
		while (l--)
			delete this.data.pop();
	else if (l < 0) {
		if (this.point !== undefined)
			this.point -= l;
		while (l++)
			this.data.unshift(" ");
	}
};
/**
 * style.js
 */

JSGadget.Display.Style = {};

JSGadget.Display.Style.OWNER = {
	overflow:   "hidden"
};
/**
 * setopt.js
 */

JSGadget.setopt = function(def, opt) {
	if (opt)
		for (var key in opt)
			if (typeof(opt[key]) == "object")
				JSGadget.setopt(def[key], opt[key]);
			else
				def[key] = opt[key];
};
