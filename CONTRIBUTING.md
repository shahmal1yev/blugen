# Contributing to Blugen

Thanks for your interest in contributing!

## Getting Started

### Prerequisites
- PHP 8.1+
- Composer

### Setup
```bash
git clone https://github.com/YOUR_USERNAME/blugen.git
cd blugen
composer install
```

### Verify Installation
```bash
./bin/blugen --version
vendor/bin/phpunit
vendor/bin/phpstan analyse
```

## Development Workflow

1. **Create a branch**
   ```bash
   git checkout -b feature/your-feature
   ```

2. **Make changes**
   - Follow PSR-12 coding standards
   - Add tests for new functionality

3. **Test your changes**
   ```bash
   vendor/bin/phpunit
   vendor/bin/phpstan analyse
   ./bin/blugen generate --help
   ```

4. **Submit a pull request**

## Coding Standards

- Follow PSR-12
- Use strict typing
- Add docblocks for public methods
- Write tests for new code

## Running Tests

```bash
# All tests
vendor/bin/phpunit

# With coverage
vendor/bin/phpunit --coverage-html coverage/

# Static analysis
vendor/bin/phpstan analyse
```

## Submitting Issues

Use the issue templates for bug reports and feature requests. Include:
- PHP version
- Steps to reproduce
- Expected vs actual behavior

## Questions?

Open a GitHub issue or discussion.