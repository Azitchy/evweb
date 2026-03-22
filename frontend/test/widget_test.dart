import 'package:flutter_test/flutter_test.dart';

import 'package:frontend/main.dart';

void main() {
  testWidgets('App loads smoke test', (WidgetTester tester) async {
    await tester.pumpWidget(const EVChargingApp());
    expect(find.text('EV Charging'), findsOneWidget);
  });
}
