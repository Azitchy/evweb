import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/subscription_provider.dart';
import '../providers/wallet_provider.dart';
import '../models/models.dart';
import 'package:intl/intl.dart';

class SubscriptionScreen extends StatefulWidget {
  const SubscriptionScreen({super.key});

  @override
  State<SubscriptionScreen> createState() => _SubscriptionScreenState();
}

class _SubscriptionScreenState extends State<SubscriptionScreen> {
  @override
  void initState() {
    super.initState();
    final provider = context.read<SubscriptionProvider>();
    provider.fetchPlans();
    provider.fetchCurrentSubscription();
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<SubscriptionProvider>();

    return Scaffold(
      appBar: AppBar(title: const Text('Subscription Plans')),
      body: provider.isLoading
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: () async {
                await provider.fetchPlans();
                await provider.fetchCurrentSubscription();
              },
              child: ListView(
                padding: const EdgeInsets.all(16),
                children: [
                  // Current subscription
                  if (provider.hasActiveSubscription) ...[
                    _CurrentSubscriptionCard(
                      subscription: provider.currentSubscription!,
                      onCancel: () async {
                        final confirmed = await showDialog<bool>(
                          context: context,
                          builder: (ctx) => AlertDialog(
                            title: const Text('Cancel Subscription'),
                            content: const Text(
                              'Are you sure you want to cancel your subscription?',
                            ),
                            actions: [
                              TextButton(
                                onPressed: () => Navigator.pop(ctx, false),
                                child: const Text('No'),
                              ),
                              TextButton(
                                onPressed: () => Navigator.pop(ctx, true),
                                child: const Text('Yes, Cancel'),
                              ),
                            ],
                          ),
                        );
                        if (confirmed == true && context.mounted) {
                          final success = await provider.cancelSubscription();
                          if (success && context.mounted) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text('Subscription cancelled'),
                              ),
                            );
                          }
                        }
                      },
                    ),
                    const SizedBox(height: 24),
                  ],

                  Text(
                    'Available Plans',
                    style: Theme.of(context).textTheme.titleLarge,
                  ),
                  const SizedBox(height: 12),

                  ...provider.plans.map(
                    (plan) => _PlanCard(
                      plan: plan,
                      isCurrentPlan:
                          provider.currentSubscription?.subscriptionPlanId ==
                          plan.id,
                      onSubscribe: provider.hasActiveSubscription
                          ? null
                          : () => _subscribe(plan),
                    ),
                  ),

                  if (provider.error != null)
                    Padding(
                      padding: const EdgeInsets.only(top: 16),
                      child: Text(
                        provider.error!,
                        style: const TextStyle(color: Colors.red),
                        textAlign: TextAlign.center,
                      ),
                    ),
                ],
              ),
            ),
    );
  }

  Future<void> _subscribe(SubscriptionPlan plan) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: Text('Subscribe to ${plan.name}'),
        content: Text(
          'Rs. ${plan.price.toStringAsFixed(0)} will be deducted from your wallet. Continue?',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx, false),
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(ctx, true),
            child: const Text('Subscribe'),
          ),
        ],
      ),
    );

    if (confirmed == true && mounted) {
      final provider = context.read<SubscriptionProvider>();
      final success = await provider.subscribe(plan.id);
      if (mounted) {
        if (success) {
          context.read<WalletProvider>().fetchBalance();
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Subscribed to ${plan.name}!')),
          );
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(provider.error ?? 'Subscription failed'),
              backgroundColor: Colors.red,
            ),
          );
        }
      }
    }
  }
}

class _CurrentSubscriptionCard extends StatelessWidget {
  final UserSubscription subscription;
  final VoidCallback onCancel;

  const _CurrentSubscriptionCard({
    required this.subscription,
    required this.onCancel,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      color: Colors.green.shade50,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                const Icon(Icons.star, color: Colors.amber, size: 28),
                const SizedBox(width: 8),
                Text(
                  'Active: ${subscription.plan?.name ?? "Plan"}',
                  style: Theme.of(context).textTheme.titleMedium?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 8),
            Text(
              'Expires: ${DateFormat('MMM dd, yyyy').format(subscription.expiresAt)}',
            ),
            if (subscription.plan != null) ...[
              const SizedBox(height: 4),
              Text(
                '${subscription.plan!.discountPercentage.toStringAsFixed(0)}% discount on charging',
              ),
            ],
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              child: OutlinedButton(
                onPressed: onCancel,
                style: OutlinedButton.styleFrom(foregroundColor: Colors.red),
                child: const Text('Cancel Subscription'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _PlanCard extends StatelessWidget {
  final SubscriptionPlan plan;
  final bool isCurrentPlan;
  final VoidCallback? onSubscribe;

  const _PlanCard({
    required this.plan,
    required this.isCurrentPlan,
    this.onSubscribe,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  plan.name,
                  style: Theme.of(
                    context,
                  ).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
                ),
                Text(
                  'Rs. ${plan.price.toStringAsFixed(0)}',
                  style: Theme.of(context).textTheme.titleLarge?.copyWith(
                    color: Colors.green.shade700,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
            Text(
              '${plan.durationDays} days',
              style: Theme.of(context).textTheme.bodySmall,
            ),
            if (plan.description != null) ...[
              const SizedBox(height: 8),
              Text(plan.description!),
            ],
            const SizedBox(height: 12),
            Wrap(
              spacing: 8,
              runSpacing: 4,
              children: [
                Chip(
                  avatar: const Icon(Icons.discount, size: 16),
                  label: Text(
                    '${plan.discountPercentage.toStringAsFixed(0)}% off',
                  ),
                ),
                if (plan.freeChargingPercentage > 0)
                  Chip(
                    avatar: const Icon(Icons.bolt, size: 16),
                    label: Text(
                      '${plan.freeChargingPercentage.toStringAsFixed(0)}% free charging',
                    ),
                  ),
                if (plan.prioritySupport)
                  const Chip(
                    avatar: Icon(Icons.support_agent, size: 16),
                    label: Text('Priority Support'),
                  ),
              ],
            ),
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: isCurrentPlan ? null : onSubscribe,
                child: Text(isCurrentPlan ? 'Current Plan' : 'Subscribe'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
