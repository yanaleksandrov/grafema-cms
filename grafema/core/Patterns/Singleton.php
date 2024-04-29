<?php

namespace Grafema\Patterns;

trait Singleton {

	/**
	 * Реальный экземпляр класса находится внутри статического поля.
	 * В этом случае статическое поле является массивом, где каждый
	 * подкласс Одиночки хранит свой собственный экземпляр.
	 *
	 * @since 1.0.0
	 */
	protected static $instance;

	/**
	 * Это статический метод, управляющий доступом к экземпляру одиночки.
	 * При первом запуске, он создаёт экземпляр одиночки и помещает его в статическое поле.
	 * При последующих запусках, он возвращает клиенту объект, хранящийся в статическом поле.
	 *
	 * Эта реализация позволяет вам расширять класс Одиночки, сохраняя повсюду
	 * только один экземпляр каждого подкласса.
	 *
	 * @since 1.0.0
	 */
	public static function init( ...$args ): self {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self( ...$args );
		}
		return self::$instance;
	}

	/**
	 * Конструктор Одиночки не должен быть публичным, а должен быть скрытым,
	 * чтобы предотвратить создание объекта через оператор new.
	 * Однако он не может быть приватным, если мы хотим разрешить создание подклассов.
	 *
	 * @since 1.0.0
	 */
	protected function __construct( ...$args ) {}

	/**
	 * Cloning and deserialization are not allowed.
	 *
	 * @since 1.0.0
	 */
	protected function __clone() {}

	/**
	 * Singleton should not be recoverable from strings.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {}
}
