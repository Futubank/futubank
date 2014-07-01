# -*- coding: utf-8 -*-
import base64
from hashlib import sha1


def get_signature(secret_key, params):
    u"""
    >>> params = {
    ...     "merchant": 43210,
    ...     "amount": '174.7',
    ...     "currency": 'RUB',
    ...     "description": u'Заказ №73138754',
    ...     "order_id": '73138754',
    ...     "success_url": 'http://myshop.ru/success/',
    ...     "fail_url": 'http://myshop.ru/fail/',
    ...     "cancel_url": 'http://myshop.ru/cart/',
    ...     "signature": '',
    ...     "unix_timestamp": '1399461194',
    ...     "meta": '{"tracking": 1234}',
    ...     "salt": '00000000000000000000000000000000',
    ... }
    >>> get_signature('C0FFEE', params)
    'cb3743cc37d87f5a4255fc3a99c223c0e869c145'
    """
    return double_sha1(secret_key, '&'.join(
        force_str(k) + '=' + base64.b64encode(force_str(params[k]))
        for k in sorted(params)
        if params[k] and k != 'signature'
    ))


def force_str(v):
    return v.encode('utf-8') if isinstance(v, unicode) else str(v)


def double_sha1(secret_key, data):
    """
    >>> double_sha1('C0FFEE', 'example')
    '27d204596505ff298ca79fb3bb949501cd7b2fa7'
    """
    for i in range(2):
        data = sha1(secret_key + data).hexdigest()
    return data


if __name__ == "__main__":
    import doctest
    doctest.testmod()
    print 'doctests passed'
