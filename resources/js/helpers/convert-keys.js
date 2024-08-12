import _ from 'lodash';

function getClass(obj) {
    const typeWithBrackets = Object.prototype.toString.call(obj);

    return typeWithBrackets.match(/\[object (.+)\]/)[1];
}

function toCamel(string) {
    let find = /(\_\w)/g;
    let convert = function (matches) {
        return _.toUpper(matches[1]);
    }

    return _.replace(string, find, convert);
}

function toSnake(string) {
    let find = /([A-Z])/g;
    let convert = function (matches) {
        return '_' + _.toLower(matches);
    }

    return _.replace(string, find, convert);
}

function convertObjectKeys(obj, keyConversionFun, ignoreKeys = []) {
    if (getClass(obj) !== 'Object' && getClass(obj) !== 'Array') {

        return obj;
    }

    return _.reduce(_.keys(obj), (newObj, key) => {
        if (_.includes(ignoreKeys, key)) {
            newObj[key] = obj[key];
        } else {
            newObj[keyConversionFun(key)] = convertObjectKeys(obj[key], keyConversionFun);
        }

        return newObj;
    }, _.isArray(obj) ? [] : {})
}

export default {
    toCamel: (o, ignoreKeys) => convertObjectKeys(o, toCamel, ignoreKeys),
    toSnake: (o, ignoreKeys) => convertObjectKeys(o, toSnake, ignoreKeys)
}
