# Scientific Calculator Feature for JAMB Exam

## Overview

A fully functional **basic** calculator has been integrated into the JAMB exam interface. The calculator is accessible via a button in the sidebar and can be opened or closed without interfering with the exam.

## Features Implemented

### 1. **Calculator Button (Sidebar)**

- Located in the exam sidebar, just above the Legend section
- Blue button with calculator icon and "Calculator" label
- Opens the calculator overlay when clicked
- Accessible on all exam pages (JAMB mock and single-subject exams)

### 2. **Scientific Functions**

The calculator supports the following functions:

#### Trigonometric Functions (in degrees)

- `sin` - Sine
- `cos` - Cosine
- `tan` - Tangent

#### Logarithmic Functions

- `log` - Base 10 logarithm
- `ln` - Natural logarithm (base e)
- `e^x` - Exponential function

#### Other Functions

- `√` - Square root
- `n!` - Factorial (for non-negative integers)
- `π` - Pi constant (3.14159...)
- `x^y` - Power/Exponent function (via ^ operator)

#### Basic Operations

- Addition (+)
- Subtraction (−)
- Multiplication (×)
- Division (÷)
- Parentheses for order of operations

### 3. **User Interface**

- **Compact Design**: Calculator is contained in a modal-like overlay that appears at the bottom of the screen
- **Dark Header**: Matches exam theme with dark blue gradient header
- **Clear Display**: Large, readable numeric display with monospace font
- **Grid Layout**: 4-column button grid for intuitive layout

### 4. **Control Buttons**

- **C** - Clear all entries (reset to 0)
- **←** - Backspace (remove last digit)
- **=** - Calculate and display result
- **Close Button (X)** - Close calculator overlay

### 5. **Keyboard Support**

When calculator is open, the following keyboard shortcuts work:

- **0-9** - Number input
- **.** - Decimal point
- **+, -, \*, /** - Basic operations
- **Enter** - Calculate result
- **Backspace** - Delete last character
- **C** - Clear calculator

### 6. **Responsive Design**

- **Desktop**: Full-sized calculator with optimal spacing
- **Tablet**: Adjusted sizing for mid-sized screens
- **Mobile**: Compact layout that scales to screen width
- Smooth animations for open/close transitions

## Visual Design

### Color Scheme

- **Background**: Dark overlay (semi-transparent)
- **Header**: Dark blue gradient (#1e3a8a to #0f172a)
- **Display**: White background with monospace font
- **Regular Buttons**: Light gray (#f1f5f9)
- **Operation Buttons**: Light blue (#e0e7ff)
- **Function Buttons**: Sky blue (#dbeafe)
- **Clear Button**: Red/pink (#fee2e2)
- **Equals Button**: Dark blue gradient with white text

### Styling Details

- Rounded corners (12-20px border-radius)
- Smooth hover effects with elevation
- Active state with inset shadows
- Grid layout for organized button placement

## Implementation Files Modified

### File: `resources/views/exam/take.blade.php`

#### Changes:

1. **CSS Styles Added** (lines 68-230)
    - `.calculator-overlay` - Full-screen overlay container
    - `.calculator-container` - Main calculator box
    - `.calculator-header` - Header with title and close button
    - `.calculator-display` - Display area for numbers
    - `.calc-screen` - Input field styling
    - `.calculator-buttons` - Button grid container
    - `.calc-btn`, `.calc-btn.op`, `.calc-btn.func`, etc. - Button variants
    - Responsive media queries for mobile/tablet

2. **HTML Overlay** (lines 373-435)
    - Complete calculator overlay markup
    - Scientific function buttons
    - Numeric pad
    - Operation buttons
    - Control buttons (C, ←, =)

3. **Sidebar Button** (lines 493-499)
    - Calculator button added to sidebar
    - Placed between "Questions" and "Legend" sections
    - Blue primary button with calculator icon

4. **JavaScript Functions** (lines 873-1027)
    - `toggleCalculator()` - Show/hide calculator
    - `appendCalc(value)` - Add value to display
    - `clearCalculator()` - Reset to 0
    - `backspaceCalculator()` - Remove last character
    - `calcFn(fn)` - Execute scientific functions
    - `factorial(n)` - Calculate factorial
    - `calculateResult()` - Evaluate expression
    - Keyboard event listeners for calculator input
    - Click event handler for outside dismissal

## How to Use

### Opening the Calculator

1. During exam, click the "Calculator" button in the sidebar
2. The calculator overlay appears at the bottom of the screen

### Performing Calculations

#### Basic Arithmetic

- Type numbers and operators
- Example: `5 + 3 =` displays `8`

#### Using Scientific Functions

- Click the function button (sin, cos, √, log, etc.)
- Example: Click `√` after `16` displays `4`

#### Complex Expressions

- Use parentheses for grouping
- Example: `(5 + 3) × 2 =` displays `16`

#### Trigonometry

- All trig functions work in degrees
- Example: `sin(90) =` displays `1`

#### Powers and Roots

- Use `x^y` button to raise to power
- Use `√` button for square root
- Example: `2 ^ 3 =` displays `8`

### Closing the Calculator

- Click the X button in the header
- Click outside the calculator area
- The calculator does NOT interfere with exam timer or questions

## Technical Features

### Error Handling

- Invalid expressions show "Error" message
- Division by zero handled gracefully
- Floating-point precision limited to 10 decimal places
- Factorial limited to non-negative integers

### Performance

- Zero impact on exam timer
- No page refresh on calculator use
- All calculations done client-side (JavaScript)
- Calculator state is isolated from exam state

### Browser Compatibility

- Works on all modern browsers (Chrome, Firefox, Safari, Edge)
- Uses standard JavaScript Math functions
- Bootstrap 5 CSS for styling consistency
- Bootstrap Icons for calculator icon

## Testing Checklist

- [x] Calculator button appears in sidebar
- [x] Calculator opens without page refresh
- [x] All number buttons work (0-9)
- [x] All operation buttons work (+, -, ×, ÷)
- [x] All scientific functions work (sin, cos, tan, √, log, ln, e^x, n!)
- [x] Parentheses work for order of operations
- [x] Clear button resets to 0
- [x] Backspace removes last digit
- [x] Keyboard input works when calculator is open
- [x] Calculator closes with X button
- [x] Calculator closes when clicking outside
- [x] Timer continues running while calculator is open
- [x] Question navigation works with calculator open
- [x] Mobile/tablet responsive design works
- [x] No impact on exam submission or answer saving

## Future Enhancement Possibilities

- Memory functions (M+, M-, MR, MC)
- Calculator history/tape view
- Conversion functions (temperature, units)
- Bitwise operations
- More advanced functions (inverse trig, hyperbolic)
- Calculation history for reference
