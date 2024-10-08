var __webpack_modules__ = {
    764: (module, exports) => {
        var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;
        (function(factory) {
            if (true) {
                !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = factory, __WEBPACK_AMD_DEFINE_RESULT__ = typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ? __WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__) : __WEBPACK_AMD_DEFINE_FACTORY__, 
                __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
            } else {}
        })((function() {
            'use strict';
            var VERSION = '14.6.4';
            function isValidFormatter(entry) {
                return typeof entry === 'object' && typeof entry.to === 'function' && typeof entry.from === 'function';
            }
            function removeElement(el) {
                el.parentElement.removeChild(el);
            }
            function isSet(value) {
                return value !== null && value !== undefined;
            }
            function preventDefault(e) {
                e.preventDefault();
            }
            function unique(array) {
                return array.filter((function(a) {
                    return !this[a] ? this[a] = true : false;
                }), {});
            }
            function closest(value, to) {
                return Math.round(value / to) * to;
            }
            function offset(elem, orientation) {
                var rect = elem.getBoundingClientRect();
                var doc = elem.ownerDocument;
                var docElem = doc.documentElement;
                var pageOffset = getPageOffset(doc);
                if (/webkit.*Chrome.*Mobile/i.test(navigator.userAgent)) {
                    pageOffset.x = 0;
                }
                return orientation ? rect.top + pageOffset.y - docElem.clientTop : rect.left + pageOffset.x - docElem.clientLeft;
            }
            function isNumeric(a) {
                return typeof a === 'number' && !isNaN(a) && isFinite(a);
            }
            function addClassFor(element, className, duration) {
                if (duration > 0) {
                    addClass(element, className);
                    setTimeout((function() {
                        removeClass(element, className);
                    }), duration);
                }
            }
            function limit(a) {
                return Math.max(Math.min(a, 100), 0);
            }
            function asArray(a) {
                return Array.isArray(a) ? a : [ a ];
            }
            function countDecimals(numStr) {
                numStr = String(numStr);
                var pieces = numStr.split('.');
                return pieces.length > 1 ? pieces[1].length : 0;
            }
            function addClass(el, className) {
                if (el.classList && !/\s/.test(className)) {
                    el.classList.add(className);
                } else {
                    el.className += ' ' + className;
                }
            }
            function removeClass(el, className) {
                if (el.classList && !/\s/.test(className)) {
                    el.classList.remove(className);
                } else {
                    el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
                }
            }
            function hasClass(el, className) {
                return el.classList ? el.classList.contains(className) : new RegExp('\\b' + className + '\\b').test(el.className);
            }
            function getPageOffset(doc) {
                var supportPageOffset = window.pageXOffset !== undefined;
                var isCSS1Compat = (doc.compatMode || '') === 'CSS1Compat';
                var x = supportPageOffset ? window.pageXOffset : isCSS1Compat ? doc.documentElement.scrollLeft : doc.body.scrollLeft;
                var y = supportPageOffset ? window.pageYOffset : isCSS1Compat ? doc.documentElement.scrollTop : doc.body.scrollTop;
                return {
                    x,
                    y
                };
            }
            function getActions() {
                return window.navigator.pointerEnabled ? {
                    start: 'pointerdown',
                    move: 'pointermove',
                    end: 'pointerup'
                } : window.navigator.msPointerEnabled ? {
                    start: 'MSPointerDown',
                    move: 'MSPointerMove',
                    end: 'MSPointerUp'
                } : {
                    start: 'mousedown touchstart',
                    move: 'mousemove touchmove',
                    end: 'mouseup touchend'
                };
            }
            function getSupportsPassive() {
                var supportsPassive = false;
                try {
                    var opts = Object.defineProperty({}, 'passive', {
                        get: function() {
                            supportsPassive = true;
                        }
                    });
                    window.addEventListener('test', null, opts);
                } catch (e) {}
                return supportsPassive;
            }
            function getSupportsTouchActionNone() {
                return window.CSS && CSS.supports && CSS.supports('touch-action', 'none');
            }
            function subRangeRatio(pa, pb) {
                return 100 / (pb - pa);
            }
            function fromPercentage(range, value, startRange) {
                return value * 100 / (range[startRange + 1] - range[startRange]);
            }
            function toPercentage(range, value) {
                return fromPercentage(range, range[0] < 0 ? value + Math.abs(range[0]) : value - range[0], 0);
            }
            function isPercentage(range, value) {
                return value * (range[1] - range[0]) / 100 + range[0];
            }
            function getJ(value, arr) {
                var j = 1;
                while (value >= arr[j]) {
                    j += 1;
                }
                return j;
            }
            function toStepping(xVal, xPct, value) {
                if (value >= xVal.slice(-1)[0]) {
                    return 100;
                }
                var j = getJ(value, xVal);
                var va = xVal[j - 1];
                var vb = xVal[j];
                var pa = xPct[j - 1];
                var pb = xPct[j];
                return pa + toPercentage([ va, vb ], value) / subRangeRatio(pa, pb);
            }
            function fromStepping(xVal, xPct, value) {
                if (value >= 100) {
                    return xVal.slice(-1)[0];
                }
                var j = getJ(value, xPct);
                var va = xVal[j - 1];
                var vb = xVal[j];
                var pa = xPct[j - 1];
                var pb = xPct[j];
                return isPercentage([ va, vb ], (value - pa) * subRangeRatio(pa, pb));
            }
            function getStep(xPct, xSteps, snap, value) {
                if (value === 100) {
                    return value;
                }
                var j = getJ(value, xPct);
                var a = xPct[j - 1];
                var b = xPct[j];
                if (snap) {
                    if (value - a > (b - a) / 2) {
                        return b;
                    }
                    return a;
                }
                if (!xSteps[j - 1]) {
                    return value;
                }
                return xPct[j - 1] + closest(value - xPct[j - 1], xSteps[j - 1]);
            }
            function handleEntryPoint(index, value, that) {
                var percentage;
                if (typeof value === 'number') {
                    value = [ value ];
                }
                if (!Array.isArray(value)) {
                    throw new Error('noUiSlider (' + VERSION + '): \'range\' contains invalid value.');
                }
                if (index === 'min') {
                    percentage = 0;
                } else if (index === 'max') {
                    percentage = 100;
                } else {
                    percentage = parseFloat(index);
                }
                if (!isNumeric(percentage) || !isNumeric(value[0])) {
                    throw new Error('noUiSlider (' + VERSION + '): \'range\' value isn\'t numeric.');
                }
                that.xPct.push(percentage);
                that.xVal.push(value[0]);
                if (!percentage) {
                    if (!isNaN(value[1])) {
                        that.xSteps[0] = value[1];
                    }
                } else {
                    that.xSteps.push(isNaN(value[1]) ? false : value[1]);
                }
                that.xHighestCompleteStep.push(0);
            }
            function handleStepPoint(i, n, that) {
                if (!n) {
                    return;
                }
                if (that.xVal[i] === that.xVal[i + 1]) {
                    that.xSteps[i] = that.xHighestCompleteStep[i] = that.xVal[i];
                    return;
                }
                that.xSteps[i] = fromPercentage([ that.xVal[i], that.xVal[i + 1] ], n, 0) / subRangeRatio(that.xPct[i], that.xPct[i + 1]);
                var totalSteps = (that.xVal[i + 1] - that.xVal[i]) / that.xNumSteps[i];
                var highestStep = Math.ceil(Number(totalSteps.toFixed(3)) - 1);
                var step = that.xVal[i] + that.xNumSteps[i] * highestStep;
                that.xHighestCompleteStep[i] = step;
            }
            function Spectrum(entry, snap, singleStep) {
                this.xPct = [];
                this.xVal = [];
                this.xSteps = [ singleStep || false ];
                this.xNumSteps = [ false ];
                this.xHighestCompleteStep = [];
                this.snap = snap;
                var index;
                var ordered = [];
                for (index in entry) {
                    if (entry.hasOwnProperty(index)) {
                        ordered.push([ entry[index], index ]);
                    }
                }
                if (ordered.length && typeof ordered[0][0] === 'object') {
                    ordered.sort((function(a, b) {
                        return a[0][0] - b[0][0];
                    }));
                } else {
                    ordered.sort((function(a, b) {
                        return a[0] - b[0];
                    }));
                }
                for (index = 0; index < ordered.length; index++) {
                    handleEntryPoint(ordered[index][1], ordered[index][0], this);
                }
                this.xNumSteps = this.xSteps.slice(0);
                for (index = 0; index < this.xNumSteps.length; index++) {
                    handleStepPoint(index, this.xNumSteps[index], this);
                }
            }
            Spectrum.prototype.getDistance = function(value) {
                var index;
                var distances = [];
                for (index = 0; index < this.xNumSteps.length - 1; index++) {
                    var step = this.xNumSteps[index];
                    if (step && value / step % 1 !== 0) {
                        throw new Error('noUiSlider (' + VERSION + '): \'limit\', \'margin\' and \'padding\' of ' + this.xPct[index] + '% range must be divisible by step.');
                    }
                    distances[index] = fromPercentage(this.xVal, value, index);
                }
                return distances;
            };
            Spectrum.prototype.getAbsoluteDistance = function(value, distances, direction) {
                var xPct_index = 0;
                if (value < this.xPct[this.xPct.length - 1]) {
                    while (value > this.xPct[xPct_index + 1]) {
                        xPct_index++;
                    }
                } else if (value === this.xPct[this.xPct.length - 1]) {
                    xPct_index = this.xPct.length - 2;
                }
                if (!direction && value === this.xPct[xPct_index + 1]) {
                    xPct_index++;
                }
                var start_factor;
                var rest_factor = 1;
                var rest_rel_distance = distances[xPct_index];
                var range_pct = 0;
                var rel_range_distance = 0;
                var abs_distance_counter = 0;
                var range_counter = 0;
                if (direction) {
                    start_factor = (value - this.xPct[xPct_index]) / (this.xPct[xPct_index + 1] - this.xPct[xPct_index]);
                } else {
                    start_factor = (this.xPct[xPct_index + 1] - value) / (this.xPct[xPct_index + 1] - this.xPct[xPct_index]);
                }
                while (rest_rel_distance > 0) {
                    range_pct = this.xPct[xPct_index + 1 + range_counter] - this.xPct[xPct_index + range_counter];
                    if (distances[xPct_index + range_counter] * rest_factor + 100 - start_factor * 100 > 100) {
                        rel_range_distance = range_pct * start_factor;
                        rest_factor = (rest_rel_distance - 100 * start_factor) / distances[xPct_index + range_counter];
                        start_factor = 1;
                    } else {
                        rel_range_distance = distances[xPct_index + range_counter] * range_pct / 100 * rest_factor;
                        rest_factor = 0;
                    }
                    if (direction) {
                        abs_distance_counter = abs_distance_counter - rel_range_distance;
                        if (this.xPct.length + range_counter >= 1) {
                            range_counter--;
                        }
                    } else {
                        abs_distance_counter = abs_distance_counter + rel_range_distance;
                        if (this.xPct.length - range_counter >= 1) {
                            range_counter++;
                        }
                    }
                    rest_rel_distance = distances[xPct_index + range_counter] * rest_factor;
                }
                return value + abs_distance_counter;
            };
            Spectrum.prototype.toStepping = function(value) {
                value = toStepping(this.xVal, this.xPct, value);
                return value;
            };
            Spectrum.prototype.fromStepping = function(value) {
                return fromStepping(this.xVal, this.xPct, value);
            };
            Spectrum.prototype.getStep = function(value) {
                value = getStep(this.xPct, this.xSteps, this.snap, value);
                return value;
            };
            Spectrum.prototype.getDefaultStep = function(value, isDown, size) {
                var j = getJ(value, this.xPct);
                if (value === 100 || isDown && value === this.xPct[j - 1]) {
                    j = Math.max(j - 1, 1);
                }
                return (this.xVal[j] - this.xVal[j - 1]) / size;
            };
            Spectrum.prototype.getNearbySteps = function(value) {
                var j = getJ(value, this.xPct);
                return {
                    stepBefore: {
                        startValue: this.xVal[j - 2],
                        step: this.xNumSteps[j - 2],
                        highestStep: this.xHighestCompleteStep[j - 2]
                    },
                    thisStep: {
                        startValue: this.xVal[j - 1],
                        step: this.xNumSteps[j - 1],
                        highestStep: this.xHighestCompleteStep[j - 1]
                    },
                    stepAfter: {
                        startValue: this.xVal[j],
                        step: this.xNumSteps[j],
                        highestStep: this.xHighestCompleteStep[j]
                    }
                };
            };
            Spectrum.prototype.countStepDecimals = function() {
                var stepDecimals = this.xNumSteps.map(countDecimals);
                return Math.max.apply(null, stepDecimals);
            };
            Spectrum.prototype.convert = function(value) {
                return this.getStep(this.toStepping(value));
            };
            var defaultFormatter = {
                to: function(value) {
                    return value !== undefined && value.toFixed(2);
                },
                from: Number
            };
            var cssClasses = {
                target: 'target',
                base: 'base',
                origin: 'origin',
                handle: 'handle',
                handleLower: 'handle-lower',
                handleUpper: 'handle-upper',
                touchArea: 'touch-area',
                horizontal: 'horizontal',
                vertical: 'vertical',
                background: 'background',
                connect: 'connect',
                connects: 'connects',
                ltr: 'ltr',
                rtl: 'rtl',
                textDirectionLtr: 'txt-dir-ltr',
                textDirectionRtl: 'txt-dir-rtl',
                draggable: 'draggable',
                drag: 'state-drag',
                tap: 'state-tap',
                active: 'active',
                tooltip: 'tooltip',
                pips: 'pips',
                pipsHorizontal: 'pips-horizontal',
                pipsVertical: 'pips-vertical',
                marker: 'marker',
                markerHorizontal: 'marker-horizontal',
                markerVertical: 'marker-vertical',
                markerNormal: 'marker-normal',
                markerLarge: 'marker-large',
                markerSub: 'marker-sub',
                value: 'value',
                valueHorizontal: 'value-horizontal',
                valueVertical: 'value-vertical',
                valueNormal: 'value-normal',
                valueLarge: 'value-large',
                valueSub: 'value-sub'
            };
            var INTERNAL_EVENT_NS = {
                tooltips: '.__tooltips',
                aria: '.__aria'
            };
            function validateFormat(entry) {
                if (isValidFormatter(entry)) {
                    return true;
                }
                throw new Error('noUiSlider (' + VERSION + '): \'format\' requires \'to\' and \'from\' methods.');
            }
            function testStep(parsed, entry) {
                if (!isNumeric(entry)) {
                    throw new Error('noUiSlider (' + VERSION + '): \'step\' is not numeric.');
                }
                parsed.singleStep = entry;
            }
            function testKeyboardPageMultiplier(parsed, entry) {
                if (!isNumeric(entry)) {
                    throw new Error('noUiSlider (' + VERSION + '): \'keyboardPageMultiplier\' is not numeric.');
                }
                parsed.keyboardPageMultiplier = entry;
            }
            function testKeyboardDefaultStep(parsed, entry) {
                if (!isNumeric(entry)) {
                    throw new Error('noUiSlider (' + VERSION + '): \'keyboardDefaultStep\' is not numeric.');
                }
                parsed.keyboardDefaultStep = entry;
            }
            function testRange(parsed, entry) {
                if (typeof entry !== 'object' || Array.isArray(entry)) {
                    throw new Error('noUiSlider (' + VERSION + '): \'range\' is not an object.');
                }
                if (entry.min === undefined || entry.max === undefined) {
                    throw new Error('noUiSlider (' + VERSION + '): Missing \'min\' or \'max\' in \'range\'.');
                }
                if (entry.min === entry.max) {
                    throw new Error('noUiSlider (' + VERSION + '): \'range\' \'min\' and \'max\' cannot be equal.');
                }
                parsed.spectrum = new Spectrum(entry, parsed.snap, parsed.singleStep);
            }
            function testStart(parsed, entry) {
                entry = asArray(entry);
                if (!Array.isArray(entry) || !entry.length) {
                    throw new Error('noUiSlider (' + VERSION + '): \'start\' option is incorrect.');
                }
                parsed.handles = entry.length;
                parsed.start = entry;
            }
            function testSnap(parsed, entry) {
                parsed.snap = entry;
                if (typeof entry !== 'boolean') {
                    throw new Error('noUiSlider (' + VERSION + '): \'snap\' option must be a boolean.');
                }
            }
            function testAnimate(parsed, entry) {
                parsed.animate = entry;
                if (typeof entry !== 'boolean') {
                    throw new Error('noUiSlider (' + VERSION + '): \'animate\' option must be a boolean.');
                }
            }
            function testAnimationDuration(parsed, entry) {
                parsed.animationDuration = entry;
                if (typeof entry !== 'number') {
                    throw new Error('noUiSlider (' + VERSION + '): \'animationDuration\' option must be a number.');
                }
            }
            function testConnect(parsed, entry) {
                var connect = [ false ];
                var i;
                if (entry === 'lower') {
                    entry = [ true, false ];
                } else if (entry === 'upper') {
                    entry = [ false, true ];
                }
                if (entry === true || entry === false) {
                    for (i = 1; i < parsed.handles; i++) {
                        connect.push(entry);
                    }
                    connect.push(false);
                } else if (!Array.isArray(entry) || !entry.length || entry.length !== parsed.handles + 1) {
                    throw new Error('noUiSlider (' + VERSION + '): \'connect\' option doesn\'t match handle count.');
                } else {
                    connect = entry;
                }
                parsed.connect = connect;
            }
            function testOrientation(parsed, entry) {
                switch (entry) {
                  case 'horizontal':
                    parsed.ort = 0;
                    break;

                  case 'vertical':
                    parsed.ort = 1;
                    break;

                  default:
                    throw new Error('noUiSlider (' + VERSION + '): \'orientation\' option is invalid.');
                }
            }
            function testMargin(parsed, entry) {
                if (!isNumeric(entry)) {
                    throw new Error('noUiSlider (' + VERSION + '): \'margin\' option must be numeric.');
                }
                if (entry === 0) {
                    return;
                }
                parsed.margin = parsed.spectrum.getDistance(entry);
            }
            function testLimit(parsed, entry) {
                if (!isNumeric(entry)) {
                    throw new Error('noUiSlider (' + VERSION + '): \'limit\' option must be numeric.');
                }
                parsed.limit = parsed.spectrum.getDistance(entry);
                if (!parsed.limit || parsed.handles < 2) {
                    throw new Error('noUiSlider (' + VERSION + '): \'limit\' option is only supported on linear sliders with 2 or more handles.');
                }
            }
            function testPadding(parsed, entry) {
                var index;
                if (!isNumeric(entry) && !Array.isArray(entry)) {
                    throw new Error('noUiSlider (' + VERSION + '): \'padding\' option must be numeric or array of exactly 2 numbers.');
                }
                if (Array.isArray(entry) && !(entry.length === 2 || isNumeric(entry[0]) || isNumeric(entry[1]))) {
                    throw new Error('noUiSlider (' + VERSION + '): \'padding\' option must be numeric or array of exactly 2 numbers.');
                }
                if (entry === 0) {
                    return;
                }
                if (!Array.isArray(entry)) {
                    entry = [ entry, entry ];
                }
                parsed.padding = [ parsed.spectrum.getDistance(entry[0]), parsed.spectrum.getDistance(entry[1]) ];
                for (index = 0; index < parsed.spectrum.xNumSteps.length - 1; index++) {
                    if (parsed.padding[0][index] < 0 || parsed.padding[1][index] < 0) {
                        throw new Error('noUiSlider (' + VERSION + '): \'padding\' option must be a positive number(s).');
                    }
                }
                var totalPadding = entry[0] + entry[1];
                var firstValue = parsed.spectrum.xVal[0];
                var lastValue = parsed.spectrum.xVal[parsed.spectrum.xVal.length - 1];
                if (totalPadding / (lastValue - firstValue) > 1) {
                    throw new Error('noUiSlider (' + VERSION + '): \'padding\' option must not exceed 100% of the range.');
                }
            }
            function testDirection(parsed, entry) {
                switch (entry) {
                  case 'ltr':
                    parsed.dir = 0;
                    break;

                  case 'rtl':
                    parsed.dir = 1;
                    break;

                  default:
                    throw new Error('noUiSlider (' + VERSION + '): \'direction\' option was not recognized.');
                }
            }
            function testBehaviour(parsed, entry) {
                if (typeof entry !== 'string') {
                    throw new Error('noUiSlider (' + VERSION + '): \'behaviour\' must be a string containing options.');
                }
                var tap = entry.indexOf('tap') >= 0;
                var drag = entry.indexOf('drag') >= 0;
                var fixed = entry.indexOf('fixed') >= 0;
                var snap = entry.indexOf('snap') >= 0;
                var hover = entry.indexOf('hover') >= 0;
                var unconstrained = entry.indexOf('unconstrained') >= 0;
                if (fixed) {
                    if (parsed.handles !== 2) {
                        throw new Error('noUiSlider (' + VERSION + '): \'fixed\' behaviour must be used with 2 handles');
                    }
                    testMargin(parsed, parsed.start[1] - parsed.start[0]);
                }
                if (unconstrained && (parsed.margin || parsed.limit)) {
                    throw new Error('noUiSlider (' + VERSION + '): \'unconstrained\' behaviour cannot be used with margin or limit');
                }
                parsed.events = {
                    tap: tap || snap,
                    drag,
                    fixed,
                    snap,
                    hover,
                    unconstrained
                };
            }
            function testTooltips(parsed, entry) {
                if (entry === false) {
                    return;
                }
                if (entry === true) {
                    parsed.tooltips = [];
                    for (var i = 0; i < parsed.handles; i++) {
                        parsed.tooltips.push(true);
                    }
                } else {
                    parsed.tooltips = asArray(entry);
                    if (parsed.tooltips.length !== parsed.handles) {
                        throw new Error('noUiSlider (' + VERSION + '): must pass a formatter for all handles.');
                    }
                    parsed.tooltips.forEach((function(formatter) {
                        if (typeof formatter !== 'boolean' && (typeof formatter !== 'object' || typeof formatter.to !== 'function')) {
                            throw new Error('noUiSlider (' + VERSION + '): \'tooltips\' must be passed a formatter or \'false\'.');
                        }
                    }));
                }
            }
            function testAriaFormat(parsed, entry) {
                parsed.ariaFormat = entry;
                validateFormat(entry);
            }
            function testFormat(parsed, entry) {
                parsed.format = entry;
                validateFormat(entry);
            }
            function testKeyboardSupport(parsed, entry) {
                parsed.keyboardSupport = entry;
                if (typeof entry !== 'boolean') {
                    throw new Error('noUiSlider (' + VERSION + '): \'keyboardSupport\' option must be a boolean.');
                }
            }
            function testDocumentElement(parsed, entry) {
                parsed.documentElement = entry;
            }
            function testCssPrefix(parsed, entry) {
                if (typeof entry !== 'string' && entry !== false) {
                    throw new Error('noUiSlider (' + VERSION + '): \'cssPrefix\' must be a string or `false`.');
                }
                parsed.cssPrefix = entry;
            }
            function testCssClasses(parsed, entry) {
                if (typeof entry !== 'object') {
                    throw new Error('noUiSlider (' + VERSION + '): \'cssClasses\' must be an object.');
                }
                if (typeof parsed.cssPrefix === 'string') {
                    parsed.cssClasses = {};
                    for (var key in entry) {
                        if (!entry.hasOwnProperty(key)) {
                            continue;
                        }
                        parsed.cssClasses[key] = parsed.cssPrefix + entry[key];
                    }
                } else {
                    parsed.cssClasses = entry;
                }
            }
            function testOptions(options) {
                var parsed = {
                    margin: 0,
                    limit: 0,
                    padding: 0,
                    animate: true,
                    animationDuration: 300,
                    ariaFormat: defaultFormatter,
                    format: defaultFormatter
                };
                var tests = {
                    step: {
                        r: false,
                        t: testStep
                    },
                    keyboardPageMultiplier: {
                        r: false,
                        t: testKeyboardPageMultiplier
                    },
                    keyboardDefaultStep: {
                        r: false,
                        t: testKeyboardDefaultStep
                    },
                    start: {
                        r: true,
                        t: testStart
                    },
                    connect: {
                        r: true,
                        t: testConnect
                    },
                    direction: {
                        r: true,
                        t: testDirection
                    },
                    snap: {
                        r: false,
                        t: testSnap
                    },
                    animate: {
                        r: false,
                        t: testAnimate
                    },
                    animationDuration: {
                        r: false,
                        t: testAnimationDuration
                    },
                    range: {
                        r: true,
                        t: testRange
                    },
                    orientation: {
                        r: false,
                        t: testOrientation
                    },
                    margin: {
                        r: false,
                        t: testMargin
                    },
                    limit: {
                        r: false,
                        t: testLimit
                    },
                    padding: {
                        r: false,
                        t: testPadding
                    },
                    behaviour: {
                        r: true,
                        t: testBehaviour
                    },
                    ariaFormat: {
                        r: false,
                        t: testAriaFormat
                    },
                    format: {
                        r: false,
                        t: testFormat
                    },
                    tooltips: {
                        r: false,
                        t: testTooltips
                    },
                    keyboardSupport: {
                        r: true,
                        t: testKeyboardSupport
                    },
                    documentElement: {
                        r: false,
                        t: testDocumentElement
                    },
                    cssPrefix: {
                        r: true,
                        t: testCssPrefix
                    },
                    cssClasses: {
                        r: true,
                        t: testCssClasses
                    }
                };
                var defaults = {
                    connect: false,
                    direction: 'ltr',
                    behaviour: 'tap',
                    orientation: 'horizontal',
                    keyboardSupport: true,
                    cssPrefix: 'noUi-',
                    cssClasses,
                    keyboardPageMultiplier: 5,
                    keyboardDefaultStep: 10
                };
                if (options.format && !options.ariaFormat) {
                    options.ariaFormat = options.format;
                }
                Object.keys(tests).forEach((function(name) {
                    if (!isSet(options[name]) && defaults[name] === undefined) {
                        if (tests[name].r) {
                            throw new Error('noUiSlider (' + VERSION + '): \'' + name + '\' is required.');
                        }
                        return true;
                    }
                    tests[name].t(parsed, !isSet(options[name]) ? defaults[name] : options[name]);
                }));
                parsed.pips = options.pips;
                var d = document.createElement('div');
                var msPrefix = d.style.msTransform !== undefined;
                var noPrefix = d.style.transform !== undefined;
                parsed.transformRule = noPrefix ? 'transform' : msPrefix ? 'msTransform' : 'webkitTransform';
                var styles = [ [ 'left', 'top' ], [ 'right', 'bottom' ] ];
                parsed.style = styles[parsed.dir][parsed.ort];
                return parsed;
            }
            function scope(target, options, originalOptions) {
                var actions = getActions();
                var supportsTouchActionNone = getSupportsTouchActionNone();
                var supportsPassive = supportsTouchActionNone && getSupportsPassive();
                var scope_Target = target;
                var scope_Base;
                var scope_Handles;
                var scope_Connects;
                var scope_Pips;
                var scope_Tooltips;
                var scope_Spectrum = options.spectrum;
                var scope_Values = [];
                var scope_Locations = [];
                var scope_HandleNumbers = [];
                var scope_ActiveHandlesCount = 0;
                var scope_Events = {};
                var scope_Self;
                var scope_Document = target.ownerDocument;
                var scope_DocumentElement = options.documentElement || scope_Document.documentElement;
                var scope_Body = scope_Document.body;
                var PIPS_NONE = -1;
                var PIPS_NO_VALUE = 0;
                var PIPS_LARGE_VALUE = 1;
                var PIPS_SMALL_VALUE = 2;
                var scope_DirOffset = scope_Document.dir === 'rtl' || options.ort === 1 ? 0 : 100;
                function addNodeTo(addTarget, className) {
                    var div = scope_Document.createElement('div');
                    if (className) {
                        addClass(div, className);
                    }
                    addTarget.appendChild(div);
                    return div;
                }
                function addOrigin(base, handleNumber) {
                    var origin = addNodeTo(base, options.cssClasses.origin);
                    var handle = addNodeTo(origin, options.cssClasses.handle);
                    addNodeTo(handle, options.cssClasses.touchArea);
                    handle.setAttribute('data-handle', handleNumber);
                    if (options.keyboardSupport) {
                        handle.setAttribute('tabindex', '0');
                        handle.addEventListener('keydown', (function(event) {
                            return eventKeydown(event, handleNumber);
                        }));
                    }
                    handle.setAttribute('role', 'slider');
                    handle.setAttribute('aria-orientation', options.ort ? 'vertical' : 'horizontal');
                    if (handleNumber === 0) {
                        addClass(handle, options.cssClasses.handleLower);
                    } else if (handleNumber === options.handles - 1) {
                        addClass(handle, options.cssClasses.handleUpper);
                    }
                    return origin;
                }
                function addConnect(base, add) {
                    if (!add) {
                        return false;
                    }
                    return addNodeTo(base, options.cssClasses.connect);
                }
                function addElements(connectOptions, base) {
                    var connectBase = addNodeTo(base, options.cssClasses.connects);
                    scope_Handles = [];
                    scope_Connects = [];
                    scope_Connects.push(addConnect(connectBase, connectOptions[0]));
                    for (var i = 0; i < options.handles; i++) {
                        scope_Handles.push(addOrigin(base, i));
                        scope_HandleNumbers[i] = i;
                        scope_Connects.push(addConnect(connectBase, connectOptions[i + 1]));
                    }
                }
                function addSlider(addTarget) {
                    addClass(addTarget, options.cssClasses.target);
                    if (options.dir === 0) {
                        addClass(addTarget, options.cssClasses.ltr);
                    } else {
                        addClass(addTarget, options.cssClasses.rtl);
                    }
                    if (options.ort === 0) {
                        addClass(addTarget, options.cssClasses.horizontal);
                    } else {
                        addClass(addTarget, options.cssClasses.vertical);
                    }
                    var textDirection = getComputedStyle(addTarget).direction;
                    if (textDirection === 'rtl') {
                        addClass(addTarget, options.cssClasses.textDirectionRtl);
                    } else {
                        addClass(addTarget, options.cssClasses.textDirectionLtr);
                    }
                    return addNodeTo(addTarget, options.cssClasses.base);
                }
                function addTooltip(handle, handleNumber) {
                    if (!options.tooltips[handleNumber]) {
                        return false;
                    }
                    return addNodeTo(handle.firstChild, options.cssClasses.tooltip);
                }
                function isSliderDisabled() {
                    return scope_Target.hasAttribute('disabled');
                }
                function isHandleDisabled(handleNumber) {
                    var handleOrigin = scope_Handles[handleNumber];
                    return handleOrigin.hasAttribute('disabled');
                }
                function removeTooltips() {
                    if (scope_Tooltips) {
                        removeEvent('update' + INTERNAL_EVENT_NS.tooltips);
                        scope_Tooltips.forEach((function(tooltip) {
                            if (tooltip) {
                                removeElement(tooltip);
                            }
                        }));
                        scope_Tooltips = null;
                    }
                }
                function tooltips() {
                    removeTooltips();
                    scope_Tooltips = scope_Handles.map(addTooltip);
                    bindEvent('update' + INTERNAL_EVENT_NS.tooltips, (function(values, handleNumber, unencoded) {
                        if (!scope_Tooltips[handleNumber]) {
                            return;
                        }
                        var formattedValue = values[handleNumber];
                        if (options.tooltips[handleNumber] !== true) {
                            formattedValue = options.tooltips[handleNumber].to(unencoded[handleNumber]);
                        }
                        scope_Tooltips[handleNumber].innerHTML = formattedValue;
                    }));
                }
                function aria() {
                    removeEvent('update' + INTERNAL_EVENT_NS.aria);
                    bindEvent('update' + INTERNAL_EVENT_NS.aria, (function(values, handleNumber, unencoded, tap, positions) {
                        scope_HandleNumbers.forEach((function(index) {
                            var handle = scope_Handles[index];
                            var min = checkHandlePosition(scope_Locations, index, 0, true, true, true);
                            var max = checkHandlePosition(scope_Locations, index, 100, true, true, true);
                            var now = positions[index];
                            var text = options.ariaFormat.to(unencoded[index]);
                            min = scope_Spectrum.fromStepping(min).toFixed(1);
                            max = scope_Spectrum.fromStepping(max).toFixed(1);
                            now = scope_Spectrum.fromStepping(now).toFixed(1);
                            handle.children[0].setAttribute('aria-valuemin', min);
                            handle.children[0].setAttribute('aria-valuemax', max);
                            handle.children[0].setAttribute('aria-valuenow', now);
                            handle.children[0].setAttribute('aria-valuetext', text);
                        }));
                    }));
                }
                function getGroup(mode, values, stepped) {
                    if (mode === 'range' || mode === 'steps') {
                        return scope_Spectrum.xVal;
                    }
                    if (mode === 'count') {
                        if (values < 2) {
                            throw new Error('noUiSlider (' + VERSION + '): \'values\' (>= 2) required for mode \'count\'.');
                        }
                        var interval = values - 1;
                        var spread = 100 / interval;
                        values = [];
                        while (interval--) {
                            values[interval] = interval * spread;
                        }
                        values.push(100);
                        mode = 'positions';
                    }
                    if (mode === 'positions') {
                        return values.map((function(value) {
                            return scope_Spectrum.fromStepping(stepped ? scope_Spectrum.getStep(value) : value);
                        }));
                    }
                    if (mode === 'values') {
                        if (stepped) {
                            return values.map((function(value) {
                                return scope_Spectrum.fromStepping(scope_Spectrum.getStep(scope_Spectrum.toStepping(value)));
                            }));
                        }
                        return values;
                    }
                }
                function generateSpread(density, mode, group) {
                    function safeIncrement(value, increment) {
                        return (value + increment).toFixed(7) / 1;
                    }
                    var indexes = {};
                    var firstInRange = scope_Spectrum.xVal[0];
                    var lastInRange = scope_Spectrum.xVal[scope_Spectrum.xVal.length - 1];
                    var ignoreFirst = false;
                    var ignoreLast = false;
                    var prevPct = 0;
                    group = unique(group.slice().sort((function(a, b) {
                        return a - b;
                    })));
                    if (group[0] !== firstInRange) {
                        group.unshift(firstInRange);
                        ignoreFirst = true;
                    }
                    if (group[group.length - 1] !== lastInRange) {
                        group.push(lastInRange);
                        ignoreLast = true;
                    }
                    group.forEach((function(current, index) {
                        var step;
                        var i;
                        var q;
                        var low = current;
                        var high = group[index + 1];
                        var newPct;
                        var pctDifference;
                        var pctPos;
                        var type;
                        var steps;
                        var realSteps;
                        var stepSize;
                        var isSteps = mode === 'steps';
                        if (isSteps) {
                            step = scope_Spectrum.xNumSteps[index];
                        }
                        if (!step) {
                            step = high - low;
                        }
                        if (low === false) {
                            return;
                        }
                        if (high === undefined) {
                            high = low;
                        }
                        step = Math.max(step, 1e-7);
                        for (i = low; i <= high; i = safeIncrement(i, step)) {
                            newPct = scope_Spectrum.toStepping(i);
                            pctDifference = newPct - prevPct;
                            steps = pctDifference / density;
                            realSteps = Math.round(steps);
                            stepSize = pctDifference / realSteps;
                            for (q = 1; q <= realSteps; q += 1) {
                                pctPos = prevPct + q * stepSize;
                                indexes[pctPos.toFixed(5)] = [ scope_Spectrum.fromStepping(pctPos), 0 ];
                            }
                            type = group.indexOf(i) > -1 ? PIPS_LARGE_VALUE : isSteps ? PIPS_SMALL_VALUE : PIPS_NO_VALUE;
                            if (!index && ignoreFirst && i !== high) {
                                type = 0;
                            }
                            if (!(i === high && ignoreLast)) {
                                indexes[newPct.toFixed(5)] = [ i, type ];
                            }
                            prevPct = newPct;
                        }
                    }));
                    return indexes;
                }
                function addMarking(spread, filterFunc, formatter) {
                    var element = scope_Document.createElement('div');
                    var valueSizeClasses = [];
                    valueSizeClasses[PIPS_NO_VALUE] = options.cssClasses.valueNormal;
                    valueSizeClasses[PIPS_LARGE_VALUE] = options.cssClasses.valueLarge;
                    valueSizeClasses[PIPS_SMALL_VALUE] = options.cssClasses.valueSub;
                    var markerSizeClasses = [];
                    markerSizeClasses[PIPS_NO_VALUE] = options.cssClasses.markerNormal;
                    markerSizeClasses[PIPS_LARGE_VALUE] = options.cssClasses.markerLarge;
                    markerSizeClasses[PIPS_SMALL_VALUE] = options.cssClasses.markerSub;
                    var valueOrientationClasses = [ options.cssClasses.valueHorizontal, options.cssClasses.valueVertical ];
                    var markerOrientationClasses = [ options.cssClasses.markerHorizontal, options.cssClasses.markerVertical ];
                    addClass(element, options.cssClasses.pips);
                    addClass(element, options.ort === 0 ? options.cssClasses.pipsHorizontal : options.cssClasses.pipsVertical);
                    function getClasses(type, source) {
                        var a = source === options.cssClasses.value;
                        var orientationClasses = a ? valueOrientationClasses : markerOrientationClasses;
                        var sizeClasses = a ? valueSizeClasses : markerSizeClasses;
                        return source + ' ' + orientationClasses[options.ort] + ' ' + sizeClasses[type];
                    }
                    function addSpread(offset, value, type) {
                        type = filterFunc ? filterFunc(value, type) : type;
                        if (type === PIPS_NONE) {
                            return;
                        }
                        var node = addNodeTo(element, false);
                        node.className = getClasses(type, options.cssClasses.marker);
                        node.style[options.style] = offset + '%';
                        if (type > PIPS_NO_VALUE) {
                            node = addNodeTo(element, false);
                            node.className = getClasses(type, options.cssClasses.value);
                            node.setAttribute('data-value', value);
                            node.style[options.style] = offset + '%';
                            node.innerHTML = formatter.to(value);
                        }
                    }
                    Object.keys(spread).forEach((function(offset) {
                        addSpread(offset, spread[offset][0], spread[offset][1]);
                    }));
                    return element;
                }
                function removePips() {
                    if (scope_Pips) {
                        removeElement(scope_Pips);
                        scope_Pips = null;
                    }
                }
                function pips(grid) {
                    removePips();
                    var mode = grid.mode;
                    var density = grid.density || 1;
                    var filter = grid.filter || false;
                    var values = grid.values || false;
                    var stepped = grid.stepped || false;
                    var group = getGroup(mode, values, stepped);
                    var spread = generateSpread(density, mode, group);
                    var format = grid.format || {
                        to: Math.round
                    };
                    scope_Pips = scope_Target.appendChild(addMarking(spread, filter, format));
                    return scope_Pips;
                }
                function baseSize() {
                    var rect = scope_Base.getBoundingClientRect();
                    var alt = 'offset' + [ 'Width', 'Height' ][options.ort];
                    return options.ort === 0 ? rect.width || scope_Base[alt] : rect.height || scope_Base[alt];
                }
                function attachEvent(events, element, callback, data) {
                    var method = function(e) {
                        e = fixEvent(e, data.pageOffset, data.target || element);
                        if (!e) {
                            return false;
                        }
                        if (isSliderDisabled() && !data.doNotReject) {
                            return false;
                        }
                        if (hasClass(scope_Target, options.cssClasses.tap) && !data.doNotReject) {
                            return false;
                        }
                        if (events === actions.start && e.buttons !== undefined && e.buttons > 1) {
                            return false;
                        }
                        if (data.hover && e.buttons) {
                            return false;
                        }
                        if (!supportsPassive) {
                            e.preventDefault();
                        }
                        e.calcPoint = e.points[options.ort];
                        callback(e, data);
                    };
                    var methods = [];
                    events.split(' ').forEach((function(eventName) {
                        element.addEventListener(eventName, method, supportsPassive ? {
                            passive: true
                        } : false);
                        methods.push([ eventName, method ]);
                    }));
                    return methods;
                }
                function fixEvent(e, pageOffset, eventTarget) {
                    var touch = e.type.indexOf('touch') === 0;
                    var mouse = e.type.indexOf('mouse') === 0;
                    var pointer = e.type.indexOf('pointer') === 0;
                    var x;
                    var y;
                    if (e.type.indexOf('MSPointer') === 0) {
                        pointer = true;
                    }
                    if (e.type === 'mousedown' && !e.buttons && !e.touches) {
                        return false;
                    }
                    if (touch) {
                        var isTouchOnTarget = function(checkTouch) {
                            return checkTouch.target === eventTarget || eventTarget.contains(checkTouch.target) || checkTouch.target.shadowRoot && checkTouch.target.shadowRoot.contains(eventTarget);
                        };
                        if (e.type === 'touchstart') {
                            var targetTouches = Array.prototype.filter.call(e.touches, isTouchOnTarget);
                            if (targetTouches.length > 1) {
                                return false;
                            }
                            x = targetTouches[0].pageX;
                            y = targetTouches[0].pageY;
                        } else {
                            var targetTouch = Array.prototype.find.call(e.changedTouches, isTouchOnTarget);
                            if (!targetTouch) {
                                return false;
                            }
                            x = targetTouch.pageX;
                            y = targetTouch.pageY;
                        }
                    }
                    pageOffset = pageOffset || getPageOffset(scope_Document);
                    if (mouse || pointer) {
                        x = e.clientX + pageOffset.x;
                        y = e.clientY + pageOffset.y;
                    }
                    e.pageOffset = pageOffset;
                    e.points = [ x, y ];
                    e.cursor = mouse || pointer;
                    return e;
                }
                function calcPointToPercentage(calcPoint) {
                    var location = calcPoint - offset(scope_Base, options.ort);
                    var proposal = location * 100 / baseSize();
                    proposal = limit(proposal);
                    return options.dir ? 100 - proposal : proposal;
                }
                function getClosestHandle(clickedPosition) {
                    var smallestDifference = 100;
                    var handleNumber = false;
                    scope_Handles.forEach((function(handle, index) {
                        if (isHandleDisabled(index)) {
                            return;
                        }
                        var handlePosition = scope_Locations[index];
                        var differenceWithThisHandle = Math.abs(handlePosition - clickedPosition);
                        var clickAtEdge = differenceWithThisHandle === 100 && smallestDifference === 100;
                        var isCloser = differenceWithThisHandle < smallestDifference;
                        var isCloserAfter = differenceWithThisHandle <= smallestDifference && clickedPosition > handlePosition;
                        if (isCloser || isCloserAfter || clickAtEdge) {
                            handleNumber = index;
                            smallestDifference = differenceWithThisHandle;
                        }
                    }));
                    return handleNumber;
                }
                function documentLeave(event, data) {
                    if (event.type === 'mouseout' && event.target.nodeName === 'HTML' && event.relatedTarget === null) {
                        eventEnd(event, data);
                    }
                }
                function eventMove(event, data) {
                    if (navigator.appVersion.indexOf('MSIE 9') === -1 && event.buttons === 0 && data.buttonsProperty !== 0) {
                        return eventEnd(event, data);
                    }
                    var movement = (options.dir ? -1 : 1) * (event.calcPoint - data.startCalcPoint);
                    var proposal = movement * 100 / data.baseSize;
                    moveHandles(movement > 0, proposal, data.locations, data.handleNumbers);
                }
                function eventEnd(event, data) {
                    if (data.handle) {
                        removeClass(data.handle, options.cssClasses.active);
                        scope_ActiveHandlesCount -= 1;
                    }
                    data.listeners.forEach((function(c) {
                        scope_DocumentElement.removeEventListener(c[0], c[1]);
                    }));
                    if (scope_ActiveHandlesCount === 0) {
                        removeClass(scope_Target, options.cssClasses.drag);
                        setZindex();
                        if (event.cursor) {
                            scope_Body.style.cursor = '';
                            scope_Body.removeEventListener('selectstart', preventDefault);
                        }
                    }
                    data.handleNumbers.forEach((function(handleNumber) {
                        fireEvent('change', handleNumber);
                        fireEvent('set', handleNumber);
                        fireEvent('end', handleNumber);
                    }));
                }
                function eventStart(event, data) {
                    if (data.handleNumbers.some(isHandleDisabled)) {
                        return false;
                    }
                    var handle;
                    if (data.handleNumbers.length === 1) {
                        var handleOrigin = scope_Handles[data.handleNumbers[0]];
                        handle = handleOrigin.children[0];
                        scope_ActiveHandlesCount += 1;
                        addClass(handle, options.cssClasses.active);
                    }
                    event.stopPropagation();
                    var listeners = [];
                    var moveEvent = attachEvent(actions.move, scope_DocumentElement, eventMove, {
                        target: event.target,
                        handle,
                        listeners,
                        startCalcPoint: event.calcPoint,
                        baseSize: baseSize(),
                        pageOffset: event.pageOffset,
                        handleNumbers: data.handleNumbers,
                        buttonsProperty: event.buttons,
                        locations: scope_Locations.slice()
                    });
                    var endEvent = attachEvent(actions.end, scope_DocumentElement, eventEnd, {
                        target: event.target,
                        handle,
                        listeners,
                        doNotReject: true,
                        handleNumbers: data.handleNumbers
                    });
                    var outEvent = attachEvent('mouseout', scope_DocumentElement, documentLeave, {
                        target: event.target,
                        handle,
                        listeners,
                        doNotReject: true,
                        handleNumbers: data.handleNumbers
                    });
                    listeners.push.apply(listeners, moveEvent.concat(endEvent, outEvent));
                    if (event.cursor) {
                        scope_Body.style.cursor = getComputedStyle(event.target).cursor;
                        if (scope_Handles.length > 1) {
                            addClass(scope_Target, options.cssClasses.drag);
                        }
                        scope_Body.addEventListener('selectstart', preventDefault, false);
                    }
                    data.handleNumbers.forEach((function(handleNumber) {
                        fireEvent('start', handleNumber);
                    }));
                }
                function eventTap(event) {
                    event.stopPropagation();
                    var proposal = calcPointToPercentage(event.calcPoint);
                    var handleNumber = getClosestHandle(proposal);
                    if (handleNumber === false) {
                        return false;
                    }
                    if (!options.events.snap) {
                        addClassFor(scope_Target, options.cssClasses.tap, options.animationDuration);
                    }
                    setHandle(handleNumber, proposal, true, true);
                    setZindex();
                    fireEvent('slide', handleNumber, true);
                    fireEvent('update', handleNumber, true);
                    fireEvent('change', handleNumber, true);
                    fireEvent('set', handleNumber, true);
                    if (options.events.snap) {
                        eventStart(event, {
                            handleNumbers: [ handleNumber ]
                        });
                    }
                }
                function eventHover(event) {
                    var proposal = calcPointToPercentage(event.calcPoint);
                    var to = scope_Spectrum.getStep(proposal);
                    var value = scope_Spectrum.fromStepping(to);
                    Object.keys(scope_Events).forEach((function(targetEvent) {
                        if ('hover' === targetEvent.split('.')[0]) {
                            scope_Events[targetEvent].forEach((function(callback) {
                                callback.call(scope_Self, value);
                            }));
                        }
                    }));
                }
                function eventKeydown(event, handleNumber) {
                    if (isSliderDisabled() || isHandleDisabled(handleNumber)) {
                        return false;
                    }
                    var horizontalKeys = [ 'Left', 'Right' ];
                    var verticalKeys = [ 'Down', 'Up' ];
                    var largeStepKeys = [ 'PageDown', 'PageUp' ];
                    var edgeKeys = [ 'Home', 'End' ];
                    if (options.dir && !options.ort) {
                        horizontalKeys.reverse();
                    } else if (options.ort && !options.dir) {
                        verticalKeys.reverse();
                        largeStepKeys.reverse();
                    }
                    var key = event.key.replace('Arrow', '');
                    var isLargeDown = key === largeStepKeys[0];
                    var isLargeUp = key === largeStepKeys[1];
                    var isDown = key === verticalKeys[0] || key === horizontalKeys[0] || isLargeDown;
                    var isUp = key === verticalKeys[1] || key === horizontalKeys[1] || isLargeUp;
                    var isMin = key === edgeKeys[0];
                    var isMax = key === edgeKeys[1];
                    if (!isDown && !isUp && !isMin && !isMax) {
                        return true;
                    }
                    event.preventDefault();
                    var to;
                    if (isUp || isDown) {
                        var multiplier = options.keyboardPageMultiplier;
                        var direction = isDown ? 0 : 1;
                        var steps = getNextStepsForHandle(handleNumber);
                        var step = steps[direction];
                        if (step === null) {
                            return false;
                        }
                        if (step === false) {
                            step = scope_Spectrum.getDefaultStep(scope_Locations[handleNumber], isDown, options.keyboardDefaultStep);
                        }
                        if (isLargeUp || isLargeDown) {
                            step *= multiplier;
                        }
                        step = Math.max(step, 1e-7);
                        step = (isDown ? -1 : 1) * step;
                        to = scope_Values[handleNumber] + step;
                    } else if (isMax) {
                        to = options.spectrum.xVal[options.spectrum.xVal.length - 1];
                    } else {
                        to = options.spectrum.xVal[0];
                    }
                    setHandle(handleNumber, scope_Spectrum.toStepping(to), true, true);
                    fireEvent('slide', handleNumber);
                    fireEvent('update', handleNumber);
                    fireEvent('change', handleNumber);
                    fireEvent('set', handleNumber);
                    return false;
                }
                function bindSliderEvents(behaviour) {
                    if (!behaviour.fixed) {
                        scope_Handles.forEach((function(handle, index) {
                            attachEvent(actions.start, handle.children[0], eventStart, {
                                handleNumbers: [ index ]
                            });
                        }));
                    }
                    if (behaviour.tap) {
                        attachEvent(actions.start, scope_Base, eventTap, {});
                    }
                    if (behaviour.hover) {
                        attachEvent(actions.move, scope_Base, eventHover, {
                            hover: true
                        });
                    }
                    if (behaviour.drag) {
                        scope_Connects.forEach((function(connect, index) {
                            if (connect === false || index === 0 || index === scope_Connects.length - 1) {
                                return;
                            }
                            var handleBefore = scope_Handles[index - 1];
                            var handleAfter = scope_Handles[index];
                            var eventHolders = [ connect ];
                            addClass(connect, options.cssClasses.draggable);
                            if (behaviour.fixed) {
                                eventHolders.push(handleBefore.children[0]);
                                eventHolders.push(handleAfter.children[0]);
                            }
                            eventHolders.forEach((function(eventHolder) {
                                attachEvent(actions.start, eventHolder, eventStart, {
                                    handles: [ handleBefore, handleAfter ],
                                    handleNumbers: [ index - 1, index ]
                                });
                            }));
                        }));
                    }
                }
                function bindEvent(namespacedEvent, callback) {
                    scope_Events[namespacedEvent] = scope_Events[namespacedEvent] || [];
                    scope_Events[namespacedEvent].push(callback);
                    if (namespacedEvent.split('.')[0] === 'update') {
                        scope_Handles.forEach((function(a, index) {
                            fireEvent('update', index);
                        }));
                    }
                }
                function isInternalNamespace(namespace) {
                    return namespace === INTERNAL_EVENT_NS.aria || namespace === INTERNAL_EVENT_NS.tooltips;
                }
                function removeEvent(namespacedEvent) {
                    var event = namespacedEvent && namespacedEvent.split('.')[0];
                    var namespace = event ? namespacedEvent.substring(event.length) : namespacedEvent;
                    Object.keys(scope_Events).forEach((function(bind) {
                        var tEvent = bind.split('.')[0];
                        var tNamespace = bind.substring(tEvent.length);
                        if ((!event || event === tEvent) && (!namespace || namespace === tNamespace)) {
                            if (!isInternalNamespace(tNamespace) || namespace === tNamespace) {
                                delete scope_Events[bind];
                            }
                        }
                    }));
                }
                function fireEvent(eventName, handleNumber, tap) {
                    Object.keys(scope_Events).forEach((function(targetEvent) {
                        var eventType = targetEvent.split('.')[0];
                        if (eventName === eventType) {
                            scope_Events[targetEvent].forEach((function(callback) {
                                callback.call(scope_Self, scope_Values.map(options.format.to), handleNumber, scope_Values.slice(), tap || false, scope_Locations.slice(), scope_Self);
                            }));
                        }
                    }));
                }
                function checkHandlePosition(reference, handleNumber, to, lookBackward, lookForward, getValue) {
                    var distance;
                    if (scope_Handles.length > 1 && !options.events.unconstrained) {
                        if (lookBackward && handleNumber > 0) {
                            distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber - 1], options.margin, 0);
                            to = Math.max(to, distance);
                        }
                        if (lookForward && handleNumber < scope_Handles.length - 1) {
                            distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber + 1], options.margin, 1);
                            to = Math.min(to, distance);
                        }
                    }
                    if (scope_Handles.length > 1 && options.limit) {
                        if (lookBackward && handleNumber > 0) {
                            distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber - 1], options.limit, 0);
                            to = Math.min(to, distance);
                        }
                        if (lookForward && handleNumber < scope_Handles.length - 1) {
                            distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber + 1], options.limit, 1);
                            to = Math.max(to, distance);
                        }
                    }
                    if (options.padding) {
                        if (handleNumber === 0) {
                            distance = scope_Spectrum.getAbsoluteDistance(0, options.padding[0], 0);
                            to = Math.max(to, distance);
                        }
                        if (handleNumber === scope_Handles.length - 1) {
                            distance = scope_Spectrum.getAbsoluteDistance(100, options.padding[1], 1);
                            to = Math.min(to, distance);
                        }
                    }
                    to = scope_Spectrum.getStep(to);
                    to = limit(to);
                    if (to === reference[handleNumber] && !getValue) {
                        return false;
                    }
                    return to;
                }
                function inRuleOrder(v, a) {
                    var o = options.ort;
                    return (o ? a : v) + ', ' + (o ? v : a);
                }
                function moveHandles(upward, proposal, locations, handleNumbers) {
                    var proposals = locations.slice();
                    var b = [ !upward, upward ];
                    var f = [ upward, !upward ];
                    handleNumbers = handleNumbers.slice();
                    if (upward) {
                        handleNumbers.reverse();
                    }
                    if (handleNumbers.length > 1) {
                        handleNumbers.forEach((function(handleNumber, o) {
                            var to = checkHandlePosition(proposals, handleNumber, proposals[handleNumber] + proposal, b[o], f[o], false);
                            if (to === false) {
                                proposal = 0;
                            } else {
                                proposal = to - proposals[handleNumber];
                                proposals[handleNumber] = to;
                            }
                        }));
                    } else {
                        b = f = [ true ];
                    }
                    var state = false;
                    handleNumbers.forEach((function(handleNumber, o) {
                        state = setHandle(handleNumber, locations[handleNumber] + proposal, b[o], f[o]) || state;
                    }));
                    if (state) {
                        handleNumbers.forEach((function(handleNumber) {
                            fireEvent('update', handleNumber);
                            fireEvent('slide', handleNumber);
                        }));
                    }
                }
                function transformDirection(a, b) {
                    return options.dir ? 100 - a - b : a;
                }
                function updateHandlePosition(handleNumber, to) {
                    scope_Locations[handleNumber] = to;
                    scope_Values[handleNumber] = scope_Spectrum.fromStepping(to);
                    var translation = 10 * (transformDirection(to, 0) - scope_DirOffset);
                    var translateRule = 'translate(' + inRuleOrder(translation + '%', '0') + ')';
                    scope_Handles[handleNumber].style[options.transformRule] = translateRule;
                    updateConnect(handleNumber);
                    updateConnect(handleNumber + 1);
                }
                function setZindex() {
                    scope_HandleNumbers.forEach((function(handleNumber) {
                        var dir = scope_Locations[handleNumber] > 50 ? -1 : 1;
                        var zIndex = 3 + (scope_Handles.length + dir * handleNumber);
                        scope_Handles[handleNumber].style.zIndex = zIndex;
                    }));
                }
                function setHandle(handleNumber, to, lookBackward, lookForward, exactInput) {
                    if (!exactInput) {
                        to = checkHandlePosition(scope_Locations, handleNumber, to, lookBackward, lookForward, false);
                    }
                    if (to === false) {
                        return false;
                    }
                    updateHandlePosition(handleNumber, to);
                    return true;
                }
                function updateConnect(index) {
                    if (!scope_Connects[index]) {
                        return;
                    }
                    var l = 0;
                    var h = 100;
                    if (index !== 0) {
                        l = scope_Locations[index - 1];
                    }
                    if (index !== scope_Connects.length - 1) {
                        h = scope_Locations[index];
                    }
                    var connectWidth = h - l;
                    var translateRule = 'translate(' + inRuleOrder(transformDirection(l, connectWidth) + '%', '0') + ')';
                    var scaleRule = 'scale(' + inRuleOrder(connectWidth / 100, '1') + ')';
                    scope_Connects[index].style[options.transformRule] = translateRule + ' ' + scaleRule;
                }
                function resolveToValue(to, handleNumber) {
                    if (to === null || to === false || to === undefined) {
                        return scope_Locations[handleNumber];
                    }
                    if (typeof to === 'number') {
                        to = String(to);
                    }
                    to = options.format.from(to);
                    to = scope_Spectrum.toStepping(to);
                    if (to === false || isNaN(to)) {
                        return scope_Locations[handleNumber];
                    }
                    return to;
                }
                function valueSet(input, fireSetEvent, exactInput) {
                    var values = asArray(input);
                    var isInit = scope_Locations[0] === undefined;
                    fireSetEvent = fireSetEvent === undefined ? true : !!fireSetEvent;
                    if (options.animate && !isInit) {
                        addClassFor(scope_Target, options.cssClasses.tap, options.animationDuration);
                    }
                    scope_HandleNumbers.forEach((function(handleNumber) {
                        setHandle(handleNumber, resolveToValue(values[handleNumber], handleNumber), true, false, exactInput);
                    }));
                    var i = scope_HandleNumbers.length === 1 ? 0 : 1;
                    for (;i < scope_HandleNumbers.length; ++i) {
                        scope_HandleNumbers.forEach((function(handleNumber) {
                            setHandle(handleNumber, scope_Locations[handleNumber], true, true, exactInput);
                        }));
                    }
                    setZindex();
                    scope_HandleNumbers.forEach((function(handleNumber) {
                        fireEvent('update', handleNumber);
                        if (values[handleNumber] !== null && fireSetEvent) {
                            fireEvent('set', handleNumber);
                        }
                    }));
                }
                function valueReset(fireSetEvent) {
                    valueSet(options.start, fireSetEvent);
                }
                function valueSetHandle(handleNumber, value, fireSetEvent, exactInput) {
                    handleNumber = Number(handleNumber);
                    if (!(handleNumber >= 0 && handleNumber < scope_HandleNumbers.length)) {
                        throw new Error('noUiSlider (' + VERSION + '): invalid handle number, got: ' + handleNumber);
                    }
                    setHandle(handleNumber, resolveToValue(value, handleNumber), true, true, exactInput);
                    fireEvent('update', handleNumber);
                    if (fireSetEvent) {
                        fireEvent('set', handleNumber);
                    }
                }
                function valueGet() {
                    var values = scope_Values.map(options.format.to);
                    if (values.length === 1) {
                        return values[0];
                    }
                    return values;
                }
                function destroy() {
                    removeEvent(INTERNAL_EVENT_NS.aria);
                    removeEvent(INTERNAL_EVENT_NS.tooltips);
                    for (var key in options.cssClasses) {
                        if (!options.cssClasses.hasOwnProperty(key)) {
                            continue;
                        }
                        removeClass(scope_Target, options.cssClasses[key]);
                    }
                    while (scope_Target.firstChild) {
                        scope_Target.removeChild(scope_Target.firstChild);
                    }
                    delete scope_Target.noUiSlider;
                }
                function getNextStepsForHandle(handleNumber) {
                    var location = scope_Locations[handleNumber];
                    var nearbySteps = scope_Spectrum.getNearbySteps(location);
                    var value = scope_Values[handleNumber];
                    var increment = nearbySteps.thisStep.step;
                    var decrement = null;
                    if (options.snap) {
                        return [ value - nearbySteps.stepBefore.startValue || null, nearbySteps.stepAfter.startValue - value || null ];
                    }
                    if (increment !== false) {
                        if (value + increment > nearbySteps.stepAfter.startValue) {
                            increment = nearbySteps.stepAfter.startValue - value;
                        }
                    }
                    if (value > nearbySteps.thisStep.startValue) {
                        decrement = nearbySteps.thisStep.step;
                    } else if (nearbySteps.stepBefore.step === false) {
                        decrement = false;
                    } else {
                        decrement = value - nearbySteps.stepBefore.highestStep;
                    }
                    if (location === 100) {
                        increment = null;
                    } else if (location === 0) {
                        decrement = null;
                    }
                    var stepDecimals = scope_Spectrum.countStepDecimals();
                    if (increment !== null && increment !== false) {
                        increment = Number(increment.toFixed(stepDecimals));
                    }
                    if (decrement !== null && decrement !== false) {
                        decrement = Number(decrement.toFixed(stepDecimals));
                    }
                    return [ decrement, increment ];
                }
                function getNextSteps() {
                    return scope_HandleNumbers.map(getNextStepsForHandle);
                }
                function updateOptions(optionsToUpdate, fireSetEvent) {
                    var v = valueGet();
                    var updateAble = [ 'margin', 'limit', 'padding', 'range', 'animate', 'snap', 'step', 'format', 'pips', 'tooltips' ];
                    updateAble.forEach((function(name) {
                        if (optionsToUpdate[name] !== undefined) {
                            originalOptions[name] = optionsToUpdate[name];
                        }
                    }));
                    var newOptions = testOptions(originalOptions);
                    updateAble.forEach((function(name) {
                        if (optionsToUpdate[name] !== undefined) {
                            options[name] = newOptions[name];
                        }
                    }));
                    scope_Spectrum = newOptions.spectrum;
                    options.margin = newOptions.margin;
                    options.limit = newOptions.limit;
                    options.padding = newOptions.padding;
                    if (options.pips) {
                        pips(options.pips);
                    } else {
                        removePips();
                    }
                    if (options.tooltips) {
                        tooltips();
                    } else {
                        removeTooltips();
                    }
                    scope_Locations = [];
                    valueSet(isSet(optionsToUpdate.start) ? optionsToUpdate.start : v, fireSetEvent);
                }
                function setupSlider() {
                    scope_Base = addSlider(scope_Target);
                    addElements(options.connect, scope_Base);
                    bindSliderEvents(options.events);
                    valueSet(options.start);
                    if (options.pips) {
                        pips(options.pips);
                    }
                    if (options.tooltips) {
                        tooltips();
                    }
                    aria();
                }
                setupSlider();
                scope_Self = {
                    destroy,
                    steps: getNextSteps,
                    on: bindEvent,
                    off: removeEvent,
                    get: valueGet,
                    set: valueSet,
                    setHandle: valueSetHandle,
                    reset: valueReset,
                    __moveHandles: function(a, b, c) {
                        moveHandles(a, b, scope_Locations, c);
                    },
                    options: originalOptions,
                    updateOptions,
                    target: scope_Target,
                    removePips,
                    removeTooltips,
                    getTooltips: function() {
                        return scope_Tooltips;
                    },
                    getOrigins: function() {
                        return scope_Handles;
                    },
                    pips
                };
                return scope_Self;
            }
            function initialize(target, originalOptions) {
                if (!target || !target.nodeName) {
                    throw new Error('noUiSlider (' + VERSION + '): create requires a single element, got: ' + target);
                }
                if (target.noUiSlider) {
                    throw new Error('noUiSlider (' + VERSION + '): Slider was already initialized.');
                }
                var options = testOptions(originalOptions, target);
                var api = scope(target, options, originalOptions);
                target.noUiSlider = api;
                return api;
            }
            return {
                __spectrum: Spectrum,
                version: VERSION,
                cssClasses,
                create: initialize
            };
        }));
    }
};

var __webpack_module_cache__ = {};

function __webpack_require__(moduleId) {
    var cachedModule = __webpack_module_cache__[moduleId];
    if (cachedModule !== undefined) {
        return cachedModule.exports;
    }
    var module = __webpack_module_cache__[moduleId] = {
        exports: {}
    };
    __webpack_modules__[moduleId](module, module.exports, __webpack_require__);
    return module.exports;
}

var __webpack_exports__ = __webpack_require__(764);