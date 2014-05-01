<?php
namespace AlreadyExtract;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class CompilerTest extends \PHPUnit_Framework_TestCase
{
    private static $pharPath;

    public static function setUpBeforeClass()
    {
        self::$pharPath = sys_get_temp_dir().'/already-exist-phar-test/already-extract.phar';
    }

    public function ensureDirectoryExists($directory)
    {
        if (!is_dir($directory)) {
            if (file_exists($directory)) {
                throw new \RuntimeException(
                    $directory.' exists and is not a directory.'
                );
            }
            if (!@mkdir($directory, 0777, true)) {
                throw new \RuntimeException(
                    $directory.' does not exist and could not be created.'
                );
            }
        }
    }

    public function testBuildPhar()
    {
        $fs = new Filesystem;
        $fs->remove(dirname(self::$pharPath));
        $this->ensureDirectoryExists(dirname(self::$pharPath));
        chdir(dirname(self::$pharPath));

        $proc = new Process('php '.escapeshellarg(__DIR__ . '/../../bin/compile'), dirname(self::$pharPath));
        $exitcode = $proc->run();
        if ($exitcode !== 0 || trim($proc->getOutput())) {
            $this->fail($proc->getOutput());
        }
        $this->assertTrue(file_exists(self::$pharPath));
    }

    public function testRunPhar()
    {
        $this->assertTrue(true);
    }
}
 