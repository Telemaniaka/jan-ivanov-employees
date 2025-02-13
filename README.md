# jan-ivanov-employees
job application technical task

## Installation

Use [composer](https://getcomposer.org/) to install the needed dependencies.

```bash
composer install
```

## Usage

Running in CLI on a csv file
```bash
composer app <path to CSV file>
```

Running the web GUI on the build in PHP Server
```bash
composer run start-dev
```
and is accessible at http://localhost:8081/

## Testing

```bash
composer analize && composer test
```