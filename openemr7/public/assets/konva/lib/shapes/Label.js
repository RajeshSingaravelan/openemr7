import { Factory } from '../Factory.js';
import { Shape } from '../Shape.js';
import { Group } from '../Group.js';
import { getNumberOrArrayOfNumbersValidator, getNumberValidator, } from '../Validators.js';
import { _registerNode } from '../Global.js';
var ATTR_CHANGE_LIST = [
    'fontFamily',
    'fontSize',
    'fontStyle',
    'padding',
    'lineHeight',
    'text',
    'width',
    'height',
], CHANGE_KONVA = 'Change.konva', NONE = 'none', UP = 'up', RIGHT = 'right', DOWN = 'down', LEFT = 'left', attrChangeListLen = ATTR_CHANGE_LIST.length;
export class Label extends Group {
    constructor(config) {
        super(config);
        this.on('add.konva', function (evt) {
            this._addListeners(evt.child);
            this._sync();
        });
    }
    getText() {
        return this.find('Text')[0];
    }
    getTag() {
        return this.find('Tag')[0];
    }
    _addListeners(text) {
        var that = this, n;
        var func = function () {
            that._sync();
        };
        for (n = 0; n < attrChangeListLen; n++) {
            text.on(ATTR_CHANGE_LIST[n] + CHANGE_KONVA, func);
        }
    }
    getWidth() {
        return this.getText().width();
    }
    getHeight() {
        return this.getText().height();
    }
    _sync() {
        var text = this.getText(), tag = this.getTag(), width, height, pointerDirection, pointerWidth, x, y, pointerHeight;
        if (text && tag) {
            width = text.width();
            height = text.height();
            pointerDirection = tag.pointerDirection();
            pointerWidth = tag.pointerWidth();
            pointerHeight = tag.pointerHeight();
            x = 0;
            y = 0;
            switch (pointerDirection) {
                case UP:
                    x = width / 2;
                    y = -1 * pointerHeight;
                    break;
                case RIGHT:
                    x = width + pointerWidth;
                    y = height / 2;
                    break;
                case DOWN:
                    x = width / 2;
                    y = height + pointerHeight;
                    break;
                case LEFT:
                    x = -1 * pointerWidth;
                    y = height / 2;
                    break;
            }
            tag.setAttrs({
                x: -1 * x,
                y: -1 * y,
                width: width,
                height: height,
            });
            text.setAttrs({
                x: -1 * x,
                y: -1 * y,
            });
        }
    }
}
Label.prototype.className = 'Label';
_registerNode(Label);
export class Tag extends Shape {
    _sceneFunc(context) {
        var width = this.width(), height = this.height(), pointerDirection = this.pointerDirection(), pointerWidth = this.pointerWidth(), pointerHeight = this.pointerHeight(), cornerRadius = this.cornerRadius();
        let topLeft = 0;
        let topRight = 0;
        let bottomLeft = 0;
        let bottomRight = 0;
        if (typeof cornerRadius === 'number') {
            topLeft = topRight = bottomLeft = bottomRight = Math.min(cornerRadius, width / 2, height / 2);
        }
        else {
            topLeft = Math.min(cornerRadius[0] || 0, width / 2, height / 2);
            topRight = Math.min(cornerRadius[1] || 0, width / 2, height / 2);
            bottomRight = Math.min(cornerRadius[2] || 0, width / 2, height / 2);
            bottomLeft = Math.min(cornerRadius[3] || 0, width / 2, height / 2);
        }
        context.beginPath();
        context.moveTo(topLeft, 0);
        if (pointerDirection === UP) {
            context.lineTo((width - pointerWidth) / 2, 0);
            context.lineTo(width / 2, -1 * pointerHeight);
            context.lineTo((width + pointerWidth) / 2, 0);
        }
        context.lineTo(width - topRight, 0);
        context.arc(width - topRight, topRight, topRight, (Math.PI * 3) / 2, 0, false);
        if (pointerDirection === RIGHT) {
            context.lineTo(width, (height - pointerHeight) / 2);
            context.lineTo(width + pointerWidth, height / 2);
            context.lineTo(width, (height + pointerHeight) / 2);
        }
        context.lineTo(width, height - bottomRight);
        context.arc(width - bottomRight, height - bottomRight, bottomRight, 0, Math.PI / 2, false);
        if (pointerDirection === DOWN) {
            context.lineTo((width + pointerWidth) / 2, height);
            context.lineTo(width / 2, height + pointerHeight);
            context.lineTo((width - pointerWidth) / 2, height);
        }
        context.lineTo(bottomLeft, height);
        context.arc(bottomLeft, height - bottomLeft, bottomLeft, Math.PI / 2, Math.PI, false);
        if (pointerDirection === LEFT) {
            context.lineTo(0, (height + pointerHeight) / 2);
            context.lineTo(-1 * pointerWidth, height / 2);
            context.lineTo(0, (height - pointerHeight) / 2);
        }
        context.lineTo(0, topLeft);
        context.arc(topLeft, topLeft, topLeft, Math.PI, (Math.PI * 3) / 2, false);
        context.closePath();
        context.fillStrokeShape(this);
    }
    getSelfRect() {
        var x = 0, y = 0, pointerWidth = this.pointerWidth(), pointerHeight = this.pointerHeight(), direction = this.pointerDirection(), width = this.width(), height = this.height();
        if (direction === UP) {
            y -= pointerHeight;
            height += pointerHeight;
        }
        else if (direction === DOWN) {
            height += pointerHeight;
        }
        else if (direction === LEFT) {
            x -= pointerWidth * 1.5;
            width += pointerWidth;
        }
        else if (direction === RIGHT) {
            width += pointerWidth * 1.5;
        }
        return {
            x: x,
            y: y,
            width: width,
            height: height,
        };
    }
}
Tag.prototype.className = 'Tag';
_registerNode(Tag);
Factory.addGetterSetter(Tag, 'pointerDirection', NONE);
Factory.addGetterSetter(Tag, 'pointerWidth', 0, getNumberValidator());
Factory.addGetterSetter(Tag, 'pointerHeight', 0, getNumberValidator());
Factory.addGetterSetter(Tag, 'cornerRadius', 0, getNumberOrArrayOfNumbersValidator(4));
