import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:provider/provider.dart';
import 'package:Evcharging/providers/auth_provider.dart';
import 'package:Evcharging/providers/wallet_provider.dart';
import 'package:Evcharging/providers/charging_provider.dart';
import 'package:Evcharging/providers/station_provider.dart';
import 'package:Evcharging/providers/subscription_provider.dart';
import 'package:Evcharging/providers/notification_provider.dart';
import 'package:Evcharging/screens/splash_screen.dart';

void main() {
  testWidgets('App loads smoke test', (WidgetTester tester) async {
    await tester.pumpWidget(
      MultiProvider(
        providers: [
          ChangeNotifierProvider(create: (_) => AuthProvider()),
          ChangeNotifierProvider(create: (_) => WalletProvider()),
          ChangeNotifierProvider(create: (_) => ChargingProvider()),
          ChangeNotifierProvider(create: (_) => StationProvider()),
          ChangeNotifierProvider(create: (_) => SubscriptionProvider()),
          ChangeNotifierProvider(create: (_) => NotificationProvider()),
        ],
        child: const MaterialApp(home: SplashScreen()),
      ),
    );

    expect(find.text('EV Charging'), findsOneWidget);
  });
}
