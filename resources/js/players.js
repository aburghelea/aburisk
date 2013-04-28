/**
 * Created with JetBrains PhpStorm.
 * User: alexandrubu
 * Date: 09.04.2013
 * Time: 14:51
 * To change this template use File | Settings | File Templates.
 */

ABURISK.players = function () {
    var players = {};
    var idx = 1;
    var _current = undefined;
    return {
        index: function (user) {

            if (user == undefined || user == null)
                return 0;

            if (players[user] == undefined) {
                players[user] = idx;
                idx++;
            }
            return players[user];
        },

        setCurrent: function (current) {
            if (typeof (current) == "string")
                current = this.index(current);
            _current = current;
        },

        getCurrent: function () {
            return _current;
        }
    }
}();
