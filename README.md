# Composer Satis Builder

Complementary tool to Satis for updating the satis.json "require" key from the project composer.json.

This is particularly useful if you are mirroring for git repositories and package zip files (dist files).

## Problem description

If you use in satis.json ``"require-all": true`, you will have all versions of all packages in the
repositories you defined which can take a lot of disk space

OR

You can choose to manually maintain the "require" key which can be time-consuming if you have a lot of packages.


## Usage

    build
        <composer> Path to the project composer.json file
        <satis> Path to the satis.json configuration file
        [-rdd|--require-dev-dependencies REQUIRE-DEV-DEPENDENCIES] Sets the "require-dev-dependencies" key
        [-rd|--require-dependencies REQUIRE-DEPENDENCIES] Sets the "require-dependencies" key
        [-rc|--add-requirements] Add the requirements from the project composer.json
        [-drc|--add-dev-requirements] Add the dev requirements from the project composer.json
        [-rr|--reset-requirements] Will reset (empty) the satis requirements (require key) before adding the requirements of the composer.json


## Example



Given

satis.json

    {
        "name": "My Repository",
        "homepage": "http://localhost:7777",
        "repositories": [
            { "type": "vcs", "url": "https://github.com/mycompany/privaterepo" },
        ],
        "require": {
        }
    }

and

composer.json

    {
        "name": "mycompany/mycompany-project",
        "require": {
            "mycompany/privaterepo": "^1.3"
        },
        "repositories": [
            {
                "packagist": false
            },
            {
                "type": "composer",
                "url": "http://localhost:7777/"
            }
        ]
    }

Clone the Composer Satis Builder:

    git clone https://github.com/AOEpeople/composer-satis-builder.git

After running

    php composer-satis-builder/bin/composer-satis-builder build composer.json satis.json --reset-requirements --add-requirements

satis.json will look like:

    {
        "name": "My Repository",
        "homepage": "http://localhost:7777",
        "repositories": [
            { "type": "vcs", "url": "https://github.com/mycompany/privaterepo" },
        ],
        "require": {
            "mycompany/privaterepo": "^1.3"
        },
    }

Now build Satis as before:

    php bin/satis build satis.json web/


## License

Composer Satis Builder is licensed under the MIT License - see the LICENSE file for details
