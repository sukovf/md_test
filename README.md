# MD test

## Installation
Clone the repository.

Get the **products.zip** file containing all source data files, and extract its contents into the project root folder,
so that the structure is as follows:
```path
<ROOT>/spare_parts_feed/*.json|*.xml
```

Build the image:
```bash
docker build -t md_test-transformer ./Docker
```

Run the transformer script:
```bash
docker run --rm -v $(pwd):/app -w /app md_test-transformer php index.php
```

Leave it for a couple of minutes and look in the **output** folder in the project root for the file **output.xml**;
that is your output containing the transformed data.