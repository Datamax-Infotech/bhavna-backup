// test.js
const assert = require('assert');
// Function to be tested
function sayHello() {
  return "Hello, World!";
}

// Helper function to run tests
function runTests(tests) {
  let passed = 0;
  let failed = 0;

  tests.forEach(test => {
    try {
      test();
      passed++;
      console.log(`✓ ${test.name}`);
    } catch (error) {
      failed++;
      console.error(`✗ ${test.name}`);
      console.error(error);
    }
  });

  console.log(`\n${passed} tests passed, ${failed} tests failed`);
}

// Tests
const tests = [
  function testSayHello() {
    assert.strictEqual(sayHello(), "Hello, World!");
  }
];

// Run the tests
runTests(tests);
