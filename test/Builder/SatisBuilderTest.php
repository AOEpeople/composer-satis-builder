<?php
namespace AOE\Composer\Satis\Tests\Generator\Builder;

use AOE\Composer\Satis\Generator\Builder\SatisBuilder;

class SatisBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldAddRequiresToComposer()
    {
        $composer = $this->getFixture('composer.multiple.json');

        $satis = new \stdClass();

        $builder = new SatisBuilder($composer, $satis);
        $builder->addRequiresFromComposer();

        $this->assertObjectHasAttribute('symfony/console', $builder->build()->require);
        $this->assertEquals('2.7.*', $builder->build()->require->{'symfony/console'});

        $this->assertObjectHasAttribute('symfony/yaml', $builder->build()->require);
        $this->assertEquals('2.4.*', $builder->build()->require->{'symfony/yaml'});

        $this->assertObjectHasAttribute('symfony/filesystem', $builder->build()->require);
        $this->assertEquals('2.5.*', $builder->build()->require->{'symfony/filesystem'});
    }

    /**
     * @test
     */
    public function shouldNotFailWithOnEmptyRequires()
    {
        $composer = $this->getFixture('composer.empty.json');

        $satis = new \stdClass();

        $builder = new SatisBuilder($composer, $satis);
        $builder->addRequiresFromComposer();
    }

    /**
     * @test
     */
    public function shouldAddDevRequiresToComposer()
    {
        $composer = $this->getFixture('composer.multiple.json');

        $satis = new \stdClass();

        $builder = new SatisBuilder($composer, $satis);
        $builder->addDevRequiresFromComposer();

        $this->assertObjectHasAttribute('phpunit/phpunit', $builder->build()->require);
        $this->assertEquals('4.7.*', $builder->build()->require->{'phpunit/phpunit'});

        $this->assertObjectHasAttribute('pdepend/pdepend', $builder->build()->require);
        $this->assertEquals('*', $builder->build()->require->{'pdepend/pdepend'});

        $this->assertObjectHasAttribute('squizlabs/php_codesniffer', $builder->build()->require);
        $this->assertEquals('*', $builder->build()->require->{'squizlabs/php_codesniffer'});
    }

    /**
     * @test
     */
    public function shouldNotFailWithOnEmptyDevRequires()
    {
        $composer = $this->getFixture('composer.empty.json');

        $satis = new \stdClass();

        $builder = new SatisBuilder($composer, $satis);
        $builder->addDevRequiresFromComposer();
    }

    /**
     * @test
     */
    public function shouldResetSatisRequires()
    {
        $composer = $this->getFixture('composer.multiple.json');

        $satis = new \stdClass();

        $builder = new SatisBuilder($composer, $satis);
        $builder->addRequiresFromComposer();

        $builder->resetSatisRequires();

        $builder->addDevRequiresFromComposer();

        $this->assertObjectNotHasAttribute('symfony/console', $builder->build()->require);
        $this->assertObjectNotHasAttribute('symfony/yaml', $builder->build()->require);
        $this->assertObjectNotHasAttribute('symfony/filesystem', $builder->build()->require);

        $this->assertObjectHasAttribute('phpunit/phpunit', $builder->build()->require);
        $this->assertObjectHasAttribute('pdepend/pdepend', $builder->build()->require);
        $this->assertObjectHasAttribute('squizlabs/php_codesniffer', $builder->build()->require);
    }

    /**
     * @test
     */
    public function shouldAddDevRequiresAndRequiresToComposer()
    {
        $composer = $this->getFixture('composer.multiple.json');

        $satis = new \stdClass();

        $builder = new SatisBuilder($composer, $satis);
        $builder->addRequiresFromComposer();
        $builder->addDevRequiresFromComposer();

        $this->assertObjectHasAttribute('symfony/console', $builder->build()->require);
        $this->assertObjectHasAttribute('symfony/yaml', $builder->build()->require);
        $this->assertObjectHasAttribute('symfony/filesystem', $builder->build()->require);
        $this->assertObjectHasAttribute('phpunit/phpunit', $builder->build()->require);
        $this->assertObjectHasAttribute('pdepend/pdepend', $builder->build()->require);
        $this->assertObjectHasAttribute('squizlabs/php_codesniffer', $builder->build()->require);
    }

    /**
     * @test
     */
    public function shouldRequireDependencies()
    {
        $composer = $this->getFixture('composer.multiple.json');
        $satis = new \stdClass();

        $builder = new SatisBuilder($composer, $satis);
        $builder->setRequireDependencies(true);

        $this->assertTrue($builder->build()->{'require-dependencies'});
    }

    /**
     * @test
     */
    public function shouldNotRequireDependencies()
    {
        $composer = $this->getFixture('composer.multiple.json');
        $satis = new \stdClass();

        $builder = new SatisBuilder($composer, $satis);
        $builder->setRequireDependencies(false);

        $this->assertFalse($builder->build()->{'require-dependencies'});
    }

    /**
     * @test
     */
    public function shouldRequireDevDependencies()
    {
        $composer = $this->getFixture('composer.multiple.json');
        $satis = new \stdClass();

        $builder = new SatisBuilder($composer, $satis);
        $builder->setRequireDevDependencies(true);

        $this->assertTrue($builder->build()->{'require-dev-dependencies'});
    }

    /**
     * @test
     */
    public function shouldNotRequireDevDependencies()
    {
        $composer = $this->getFixture('composer.multiple.json');
        $satis = new \stdClass();

        $builder = new SatisBuilder($composer, $satis);
        $builder->setRequireDevDependencies(false);

        $this->assertFalse($builder->build()->{'require-dev-dependencies'});
    }

    /**
     * @param string $name
     * @return \stdClass
     */
    private function getFixture($name)
    {
        $contents = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $name);
        return json_decode($contents);
    }
}
