<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Helpers;

use Magnate\Exceptions\NoticerException;

/**
 * Class for creating notices.
 * @since 0.7.0
 */
class Noticer
{

    /**
     * @var string $type
     * Notice type.
     * @since 0.7.0
     */
    protected $type = '';

    /**
     * @var string $text
     * Notice text.
     * @since 0.7.0
     */
    protected $text = '';

    /**
     * @var string $n
     * Whole notice.
     * @since 0.7.0
     */
    protected $n = '';

    public function __construct(string $type, string $text)
    {
        
        $this->type = $type;
        $this->text = $text;

        $this->assembling();

    }

    /**
     * Output the notice.
     * @since 0.7.0
     * 
     * @param string $hook
     * Hook name.
     * 
     * @param bool $echo
     * Determines whether notice need to be outputtted by echo.
     * 
     * @return string
     * 
     * @throws Magnate\Exceptions\NoticerException
     */
    public function notice(string $hook, bool $echo = true) : string
    {

        if (empty($hook)) throw new NoticerException(
            sprintf(NoticerException::pickMessage(
                NoticerException::EMPTY
            ), 'Hook'),
            NoticerException::pickCode(NoticerException::EMPTY)
        );

        if ($echo) {

            add_action($hook, function() {

                echo $this->n;

            });

        } else {

            add_filter($hook, function() {

                return $this->n;

            });

        }

        return $this->n;

    }

    /**
     * Notice HTML assembling.
     * @since 0.7.0
     * 
     * @return $this
     */
    protected function assembling() : self
    {

        ob_start();

?>
<div class="notice notice-<?= htmlspecialchars($this->type) ?> is-dismissible" style="max-width: 500px; margin: 1rem auto;">
    <p style="text-align: center;"><?= $this->text ?></p>
</div>
<?php

        $this->n = ob_get_clean();

        return $this;

    }

}
