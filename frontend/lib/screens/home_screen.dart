import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../providers/wallet_provider.dart';
import '../providers/charging_provider.dart';
import '../providers/notification_provider.dart';
import 'charging_screen.dart';
import 'wallet_screen.dart';
import 'profile_screen.dart';
import 'history_screen.dart';
import 'login_screen.dart';
import 'station_locator_screen.dart';
import 'subscription_screen.dart';
import 'notification_screen.dart';
import 'payment_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;

  late final List<Widget> _pages;

  @override
  void initState() {
    super.initState();
    _pages = [
      _DashboardPage(onTabChange: (i) => setState(() => _currentIndex = i)),
      const StationLocatorScreen(),
      const ChargingScreen(),
      const WalletScreen(),
      const ProfileScreen(),
    ];
    _loadData();
  }

  void _loadData() {
    context.read<WalletProvider>().fetchBalance();
    context.read<ChargingProvider>().fetchActiveSession();
    context.read<NotificationProvider>().fetchUnreadCount();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _pages[_currentIndex],
      bottomNavigationBar: NavigationBar(
        selectedIndex: _currentIndex,
        onDestinationSelected: (i) => setState(() => _currentIndex = i),
        destinations: const [
          NavigationDestination(
            icon: Icon(Icons.dashboard_outlined),
            selectedIcon: Icon(Icons.dashboard),
            label: 'Home',
          ),
          NavigationDestination(
            icon: Icon(Icons.location_on_outlined),
            selectedIcon: Icon(Icons.location_on),
            label: 'Stations',
          ),
          NavigationDestination(
            icon: Icon(Icons.ev_station_outlined),
            selectedIcon: Icon(Icons.ev_station),
            label: 'Charge',
          ),
          NavigationDestination(
            icon: Icon(Icons.account_balance_wallet_outlined),
            selectedIcon: Icon(Icons.account_balance_wallet),
            label: 'Wallet',
          ),
          NavigationDestination(
            icon: Icon(Icons.person_outlined),
            selectedIcon: Icon(Icons.person),
            label: 'Profile',
          ),
        ],
      ),
    );
  }
}

class _DashboardPage extends StatelessWidget {
  final ValueChanged<int> onTabChange;

  const _DashboardPage({required this.onTabChange});

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    final wallet = context.watch<WalletProvider>();
    final charging = context.watch<ChargingProvider>();
    final notifications = context.watch<NotificationProvider>();

    return Scaffold(
      appBar: AppBar(
        title: Text('Hi, ${auth.user?.name ?? 'User'}'),
        actions: [
          Stack(
            children: [
              IconButton(
                icon: const Icon(Icons.notifications_outlined),
                onPressed: () {
                  Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (_) => const NotificationScreen(),
                    ),
                  );
                },
              ),
              if (notifications.unreadCount > 0)
                Positioned(
                  right: 8,
                  top: 8,
                  child: Container(
                    padding: const EdgeInsets.all(4),
                    decoration: const BoxDecoration(
                      color: Colors.red,
                      shape: BoxShape.circle,
                    ),
                    child: Text(
                      '${notifications.unreadCount}',
                      style: const TextStyle(color: Colors.white, fontSize: 10),
                    ),
                  ),
                ),
            ],
          ),
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () async {
              await context.read<AuthProvider>().logout();
              if (!context.mounted) return;
              Navigator.of(context).pushAndRemoveUntil(
                MaterialPageRoute(builder: (_) => const LoginScreen()),
                (_) => false,
              );
            },
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          await wallet.fetchBalance();
          await charging.fetchActiveSession();
          await notifications.fetchUnreadCount();
        },
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            // Wallet Balance Card
            Card(
              color: Theme.of(context).colorScheme.primaryContainer,
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Wallet Balance',
                      style: Theme.of(context).textTheme.titleMedium,
                    ),
                    const SizedBox(height: 8),
                    Text(
                      '₹ ${wallet.balance.toStringAsFixed(2)}',
                      style: Theme.of(context).textTheme.headlineLarge
                          ?.copyWith(fontWeight: FontWeight.bold),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),

            // Active Charging Status
            if (charging.isCharging) ...[
              Card(
                color: Colors.orange.shade50,
                child: ListTile(
                  leading: const Icon(
                    Icons.bolt,
                    color: Colors.orange,
                    size: 32,
                  ),
                  title: const Text('Charging in Progress'),
                  subtitle: Text(
                    'Started at ${charging.activeSession!.startPercentage.toStringAsFixed(0)}%',
                  ),
                  trailing: const Icon(Icons.arrow_forward_ios, size: 16),
                ),
              ),
              const SizedBox(height: 16),
            ],

            // Quick Actions
            Text(
              'Quick Actions',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.ev_station,
                    label: 'Start\nCharging',
                    color: Colors.green,
                    onTap: () => onTabChange(2),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.location_on,
                    label: 'Find\nStations',
                    color: Colors.orange,
                    onTap: () => onTabChange(1),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.add_card,
                    label: 'Add\nMoney',
                    color: Colors.blue,
                    onTap: () => onTabChange(3),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.payment,
                    label: 'Online\nPayment',
                    color: Colors.teal,
                    onTap: () {
                      Navigator.of(context).push(
                        MaterialPageRoute(
                          builder: (_) => const PaymentScreen(),
                        ),
                      );
                    },
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.star,
                    label: 'Sub-\nscriptions',
                    color: Colors.amber.shade700,
                    onTap: () {
                      Navigator.of(context).push(
                        MaterialPageRoute(
                          builder: (_) => const SubscriptionScreen(),
                        ),
                      );
                    },
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.history,
                    label: 'Charging\nHistory',
                    color: Colors.purple,
                    onTap: () {
                      Navigator.of(context).push(
                        MaterialPageRoute(
                          builder: (_) => const HistoryScreen(),
                        ),
                      );
                    },
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _QuickActionCard extends StatelessWidget {
  final IconData icon;
  final String label;
  final Color color;
  final VoidCallback onTap;

  const _QuickActionCard({
    required this.icon,
    required this.label,
    required this.color,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            children: [
              Icon(icon, size: 32, color: color),
              const SizedBox(height: 8),
              Text(
                label,
                textAlign: TextAlign.center,
                style: Theme.of(context).textTheme.bodySmall,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
