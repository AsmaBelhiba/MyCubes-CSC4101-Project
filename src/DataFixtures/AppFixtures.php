<?php

namespace App\DataFixtures;

use App\Entity\Cube;
use App\Entity\CubeCollection;
use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AppFixtures extends Fixture
{
    private array $temporaryFiles = [];
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Generates initialization data for members :
     *  [email, plain text password]
     * @return \Generator
     */
    private function membersGenerator()
    {
        yield ['olivier@localhost', '123456'];
        yield ['slash@localhost', '123456'];
    }
    
    
    private const IMAGES_DIR = __DIR__ . '/../../resources/images/';
    
    private function prepareImage(string $filename): UploadedFile
    {
        $filesystem = new Filesystem();
        
        $source = Path::join(self::IMAGES_DIR, $filename);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        $temporaryFile = $filesystem->tempnam(
            sys_get_temp_dir(),
            'fixture-upload-',
            '.' . $extension
            );
        
        $filesystem->copy($source, $temporaryFile, true);
        $this->temporaryFiles[] = $temporaryFile;
        
        return new UploadedFile(
            $temporaryFile,
            $filename,
            mime_content_type($temporaryFile),
            null,
            true
            );
    }
    
    
    private function cleanupTemporaryImages(): void
    {
        $filesystem = new Filesystem();
        
        foreach ($this->temporaryFiles as $file) {
            if ($filesystem->exists($file)) {
                $filesystem->remove($file);
            }
        }
        
        $this->temporaryFiles = [];
    }
    
    

    public function load(ObjectManager $manager): void
    {
        foreach ($this->membersGenerator() as [$email, $plainPassword]) {
            $user = new Member();

            $password = $this->hasher->hashPassword($user, $plainPassword);

            $user->setEmail($email);
            $user->setPassword($password);

            $collection = new CubeCollection();
            $collection->setDescription('Collection de ' . $email);

            $user->setCubeCollection($collection);

            $cube1 = new Cube();
            $cube1->setDescription('Cube Rubik 3x3');
            $cube1->setImageFile(
                $this->prepareImage('cube 3f3.avif')
                );
            $collection->addCube($cube1);
            
            $cube2 = new Cube();
            $cube2->setDescription('Cube Rubik 4x4');
            $cube2->setImageFile(
                $this->prepareImage('cube 4f4.jpg')
                );
            $collection->addCube($cube2);

            $manager->persist($user);
            $manager->persist($collection);
        }

        $manager->flush();
        $this->cleanupTemporaryImages();
    }
}
