/**
 * Created with JetBrains PhpStorm.
 * User: iceman
 * Date: 4/30/13
 * Time: 6:37 PM
 * To change this template use File | Settings | File Templates.
 */
function timerCreator() {
    var _time = 0, _timer, _action, _signal;

    function defaultAction(_time) {
        console.log("Default " + _time);
    }

    function defaultSignal() {
        console.log("Default Time done");
    }

    function tick() {
        _time--;
        if (_time > 0) {
            _action(_time);
            armTimer();
        } else {
            _signal();
        }
    }

    function armTimer() {
        _timer = setTimeout(tick, 1000);
    }

    function disarm() {
        if (_timer) {
            clearTimeout(_timer);
        }
        _time = 0;
        _action = defaultAction;
        console.log("Disarming timer");
    }

    return {
        init: function (action, signal) {
            console.log("Timer init " + _time);
            if (_time <= 0) {
                console.log("Arming");
                _time = 30;
                _action = action instanceof Function ? action : defaultAction;
                _signal = signal instanceof Function ? signal : defaultSignal;
                armTimer();
            }
        },
        disarm: disarm
    }

}
ABURISK.timer = timerCreator();
ABURISK.notifier = timerCreator();