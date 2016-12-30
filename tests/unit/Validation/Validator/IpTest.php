<?php

namespace Phalcon\Test\Unit\Validation\Validator;

use Phalcon\Test\Module\UnitTest;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Ip;

/**
 * \Phalcon\Test\Unit\Validation\Validator\DateTest
 * Tests the \Phalcon\Validation\Validator\Date component
 *
 * @copyright (c) 2011-2016 Phalcon Team
 * @link      http://www.phalconphp.com
 * @author    Gorka Guridi <gorka.guridi@gmail.com>
 * @package   Phalcon\Test\Unit\Validation\Validator
 *
 * The contents of this file are subject to the New BSD License that is
 * bundled with this package in the file docs/LICENSE.txt
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@phalconphp.com
 * so that we can send you a copy immediately.
 */
class IpTest extends UnitTest
{
    /**
     * Tests date validator with single field
     *
     * @author Gorka Guridi <gorka.guridi@gmail.com>
     * @since  2016-12-17
     */
    public function testSingleField()
    {
        $this->specify('Test single ip address validator with single field.', function () {
            $validation = new Validation();
            $validation->add('ip', new Ip([
                'message' => 'This is a test message',
                'version' => Ip::VERSION_4,
                'allowPrivate' => true,
                'allowReserved' => true,
                'allowEmpty' => true,
            ]));

            $messages = $validation->validate(['ip' => '127.0.0.1']);
            $this->assertEmpty($messages, 'Ip address should be valid');
            $messages = $validation->validate(['ip' => '192.168.10.20']);
            $this->assertEmpty($messages, 'Ip address should be valid');
            $messages = $validation->validate(['ip' => '']);
            $this->assertEmpty($messages, 'Empty ip address should be valid.');
            $messages = $validation->validate(['ip' => '2001:cdba:0000:0000:0000:0000:3257:9652']);
            $this->assertNotEmpty($messages, 'Ip version 6 should not be valid');
            $this->assertEquals((string) $messages[0], 'This is a test message');

            $validation = new Validation();
            $validation->add('ip', new Ip([
                'message' => 'This is a test message',
                'version' => Ip::VERSION_4 | Ip::VERSION_6,
                'allowPrivate' => false,
                'allowReserved' => false,
                'allowEmpty' => false,
        	  ]));

            $messages = $validation->validate(['ip' => '127.0.0.1']);
            $this->assertNotEmpty($messages, 'Ip address should not be valid');
            $messages = $validation->validate(['ip' => '192.168.10.20']);
            $this->assertNotEmpty($messages, 'Ip address should not be valid');
            $messages = $validation->validate(['ip' => '']);
            $this->assertNotEmpty($messages, 'Empty ip address should not be valid.');
            $messages = $validation->validate(['ip' => '2001:cdba:0000:0000:0000:0000:3257:9652']);
            $this->assertEmpty($messages, 'Ip version 6 should be valid');
        });
    }

    /**
     * Tests ip addresses with multiple field
     *
     * @author Gorka Guridi <gorka.guridi@gmail.com>
     * @since  2016-12-17
     */
    public function testMultipleField()
    {
        $this->specify('Test multiple ip addresses behaviour.', function () {
            $validation = new Validation();
            $validation->add(['ip', 'anotherIp'], new Ip([
                'message' => [
                    'ip' => 'This is a test message for ip',
                    'anotherIp' => 'This is a test message for another ip',
                ],
                'version' => [
                    'ip' => Ip::VERSION_4,
                    'anotherIp' => Ip::VERSION_6,
                ],
                'allowPrivate' => [
                    'ip' => true,
                    'anotherIp' => false,
                ],
                'allowReserved' => [
                    'ip' => true,
                    'anotherIp' => false,
                ],
                'allowEmpty' => [
                    'ip' => false,
                    'anotherIp' => true,
                ],
            ]));

            $messages = $validation->validate([
                'ip' => '127.0.0.1', 
                'anotherIp' => '127.0.0.1',
            ]);
            expect($messages->count())->equals(1);
            $messages = $validation->validate([
                'ip' => '192.168.10.20', 
                'anotherIp' => '192.168.10.20',
            ]);
            expect($messages->count())->equals(1);
            $messages = $validation->validate([
                'ip' => '192.168.10.20', 
                'anotherIp' => '',
            ]);
            expect($messages->count())->equals(0);
            $messages = $validation->validate([
                'ip' => '2001:cdba:0000:0000:0000:0000:3257:9652', 
                'anotherIp' => '2001:cdba:0000:0000:0000:0000:3257:9652',
            ]);
            expect($messages->count())->equals(1);
        });
    }
}
